<?php

namespace Happyr\GoogleAnalyticsBundle\Service;

use Psr\Cache\CacheItemPoolInterface;

/**
 * This service fetches data from the API.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DataFetcher
{
    /**
     * @var CacheItemPoolInterface cache
     */
    protected $cache;

    /**
     * @var \Google_Client client
     */
    protected $client;

    /**
     * @var int profileId
     */
    protected $viewId;

    /**
     * @var int cacheLifetime
     */
    protected $cacheLifetime;

    /**
     * @param CacheItemPoolInterface $cache
     * @param \Google_Client         $client
     * @param int                    $viewId
     * @param int                    $cacheLifetime seconds
     */
    public function __construct(CacheItemPoolInterface $cache, \Google_Client $client, $viewId, $cacheLifetime)
    {
        $this->cache = $cache;
        $this->client = $client;
        $this->viewId = $viewId;
        $this->cacheLifetime = $cacheLifetime;
    }

    /**
     * Get page views for the given url.
     *
     * @param string         $uri
     * @param \DateTime|null $startTime
     * @param \DateTime|null $endTime
     * @param string         $regex
     *
     * @return int
     */
    public function getPageViews($uri, \DateTime $startTime = null, \DateTime $endTime = null, $regex = '$')
    {
        if (empty($this->viewId)) {
            throw new \LogicException('You need to specify a profile id that we are going to fetch page views from');
        }

        if ($startTime === null) {
            // one year ago
            $startTime = new \DateTime('-1year');
        }

        if ($endTime === null) {
            // today
            $endTime = new \DateTime();
        }

        $start = $startTime->format('Y-m-d');
        $end = $endTime->format('Y-m-d');

        //create the cache key
        $cacheKey = sha1($uri.$regex.$start);
        $item = $this->cache->getItem($cacheKey);
        if (!$item->isHit()) {
            //check if we got a token
            if (null === $this->client->getAccessToken()) {
                return 0;
            }

            //remove the app_dev.php
            $uri = str_replace('/app_dev.php/', '/', $uri);

            //fetch result
            try {
                $analytics = new \Google_Service_Analytics($this->client);
                $results = $analytics->data_ga->get(
                    'ga:'.$this->viewId,
                    $start,
                    $end,
                    'ga:pageviews',
                    ['filters' => 'ga:pagePath=~^'.$uri.$regex]
                );

                $rows = $results->getRows();
                $visits = intval($rows[0][0]);
            } catch (\Google_Auth_Exception $e) {
                $visits = 0;
            }

            //save cache item
            $item->set($visits)
                ->expiresAfter($this->cacheLifetime);
            $this->cache->save($item);
        }

        return $item->get();
    }
}

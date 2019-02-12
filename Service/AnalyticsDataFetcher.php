<?php

namespace Happyr\GoogleAnalyticsBundle\Service;

use Psr\Cache\CacheItemPoolInterface;

/**
 * This service fetches data from the API.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AnalyticsDataFetcher
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

    public function __construct(CacheItemPoolInterface $cache, \Google_Client $client, string $viewId, int $cacheLifetime)
    {
        $this->cache = $cache;
        $this->client = $client;
        $this->viewId = $viewId;
        $this->cacheLifetime = $cacheLifetime;
    }

    /**
     * Get page views for the given url.
     *
     * @return mixed
     */
    public function getPageViews(string $uri, \DateTimeInterface $startTime = null, \DateTimeInterface $endTime = null, string $regex = '$', array $params = [])
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
        $cacheKey = sha1($uri.$regex.$start.json_encode($params));
        $item = $this->cache->getItem($cacheKey);
        if (!$item->isHit()) {
            //check if we got a token
            if (null === $this->client->getAccessToken()) {
                return [];
            }

            $params['filters'] = 'ga:pagePath=~^'.$uri.$regex;
            try {
                $analytics = new \Google_Service_Analytics($this->client);
                $results = $analytics->data_ga->get('ga:'.$this->viewId, $start, $end, 'ga:pageviews', $params);

                $rows = $results->getRows();
            } catch (\Google_Auth_Exception $e) {
                $rows = [];
            }

            //save cache item
            $item->set($rows)
                ->expiresAfter($this->cacheLifetime);
            $this->cache->save($item);
        }

        return $item->get();
    }

    public function getPageViewsAsInteger(string $uri, \DateTimeInterface $startTime = null, \DateTimeInterface $endTime = null, string $regex = '$'): int
    {
        $rows = $this->getPageViews($uri, $startTime, $endTime, $regex);

        return intval($rows[0][0] ?? 0);
    }
}

<?php

namespace HappyR\Google\AnalyticsBundle\Services;

use HappyR\Google\AnalyticsBundle\Entity\GoogleApiReportToken;
use HappyR\Google\ApiBundle\Services\AnalyticsService;

/**
 * Class PageStatisticsService
 *
 * This service fetches data from the API
 */
class PageStatisticsService
{
    protected $analytics;//The API
    private $tokenService;
    private $config;
    protected $cache;

    /**
     * @param AnalyticsService $analyticsService
     * @param TokenService $tokenService
     * @param mixed $cache
     * @param array $config
     */
    public function __construct(AnalyticsService $analyticsService, TokenService $tokenService, $cache, array $config)
    {
        $this -> analytics = $analyticsService;
        $this -> tokenService = $tokenService;
        $this->config=$config;
        $this->cache=$cache;
    }

    /**
     * Returns true if we have a access token
     *
     * @return bool
     */
    private function hasAccessToken()
    {
        $token = $this->tokenService->getToken();
        if (!$token){
            return false;
        }

        $this->analytics->client->setAccessToken($token);

        return $this->analytics->client->getAccessToken();
    }

    /**
     * The access token might get refreshed so we need to save it after each request
     */
    private function saveAccessToken()
    {
        $token = $this->analytics->client->getAccessToken();

        //save token
        $this->tokenService->setToken($token);
    }

    /**
     * return the page views for the given url
     *
     * @param string $uri
     * @param null $since
     * @param string $regex
     *
     * @return int
     */
    public function getPageViews($uri, $since = null, $regex='$')
    {
        if (!$since){
            //one year ago
            $since = date('Y-m-d', time() - 86400 * 365);
        }

        $this->cache->setNamespace('PageStatistics.PageViews');
        $cacheKey=md5($uri.$since).time();
        if (false === ($visits = $this->cache->fetch($cacheKey))) {
            //check if we got a token
            if (!$this -> hasAccessToken()) {
                return 0;
            }

            $uri=str_replace('/app_dev.php/', '/', $uri);

            //fetch result
            try {
                $results = $this->analytics->data_ga->get(
                    'ga:'.$this->config['profile_id'],
                    $since, date('Y-m-d'),
                    'ga:pageviews',
                    array('filters' => 'ga:pagePath=~^'.$uri.$regex)
                );

                $rows = $results -> getRows();
                $visits = intval($rows[0][0]);
            } catch (\Google_AuthException $e) {
                $visits=0;
            }

            //save cache (TTL 1h)
            $this->cache->save($cacheKey, $visits, 3600);

            //save access token
            $this -> saveAccessToken();
        }

        return $visits;
    }
}

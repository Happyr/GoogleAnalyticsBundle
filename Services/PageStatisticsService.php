<?php

namespace HappyR\Google\AnalyticsBundle\Services;

use HappyR\Google\AnalyticsBundle\Entity\GoogleApiReportToken;

class PageStatisticsService
{
    protected $analytics;//The API
    private $em;
    private $config;
    protected $cache;

    public function __construct($analyticsService, $em, $cache, $config)
    {
        $this -> analytics = $analyticsService;
        $this -> em = $em;
        $this->config=$config;
        $this->cache=$cache;
    }

    /**
     * Returns true if we have a access token
     */
    private function hasAccessToken()
    {
        $token = $this->em->getRepository('HappyRGoogleAnalyticsBundle:GoogleApiReportToken')->findOne();
        if (!$token)
            return false;

        $this->analytics->client->setAccessToken($token->getToken());

        return $this->analytics->client->getAccessToken();
    }

    /**
     * The access token might get refreshed so we need to save it after each request
     */
    private function saveAccessToken()
    {
        $token = $this -> em -> getRepository('HappyRGoogleAnalyticsBundle:GoogleApiReportToken') -> findOne();
        if (!$token) {
            $token = new GoogleApiReportToken();
        }

        $token->setToken($this->analytics->client->getAccessToken());

        $this -> em -> persist($token);
        $this -> em -> flush();

    }

    /**
     * return the page views for the given url
     */
    public function getPageViews($uri, $since = null, $regex='$')
    {
        if (!$since)
           $since = date('Y-m-d', time() - 86400 * 365); //one year ago

        $this->cache->setNamespace('PageStatistics.PageViews');
        $cache_key=md5($uri.$since).time();
        if (false === ($visits = $this->cache->fetch($cache_key))) {
            //check if we got a token
            if (!$this -> hasAccessToken()) {
                return 0;
            }

            $uri=str_replace('/app_dev.php/', '/', $uri);

            //fetch result
            try {
                $results = $this->analytics->data_ga->get('ga:'.$this->config['profile_id'], $since, date('Y-m-d'), 'ga:pageviews', array('filters' => 'ga:pagePath=~^'.$uri.$regex));
                $rows = $results -> getRows();
                $visits = intval($rows[0][0]);
            } catch (\Google_AuthException $e) {
                $visits=0;
            }

            //save cache
            $this->cache->save($cache_key, $visits, 3600);//TTL 1h

            //save access token
            $this -> saveAccessToken();
        }

        return $visits;

    }

}

<?php


namespace HappyR\Google\AnalyticsBundle\Services;


/**
 * A service that handles tokens
 * 
 */
class TokenService 
{
    protected $path;
    protected $tokenPrefix='google-analytics-token';

    /**
     * @param string $path to token
     */
    function __construct($path)
    {
        $this->path = $path;
    }


    public function getToken($name='')
    {

    }

    public function setToken($name='')
    {

    }
}
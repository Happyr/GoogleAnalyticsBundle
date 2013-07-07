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
        $path=rtrim($path,'/').'/';

        $this->path = $path;
    }

    /**
     * Get a token or null if fail
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getToken($name='')
    {
        $filename=$this->tokenPrefix.$name;

        $token=@file_get_contents($this->path.$filename);

        if($token===false){
            return null;
        }

        return $token;
    }

    /**
     *
     *
     * @param string $token
     * @param string $name
     *
     * @return bool true if success
     */
    public function setToken($token, $name='')
    {
        $filename=$this->tokenPrefix.$name;

        return file_put_contents($this->path.$filename, $token);
    }
}
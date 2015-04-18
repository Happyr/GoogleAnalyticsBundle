<?php
namespace Happyr\GoogleAnalyticsBundle\Http;

/**
 * Class Tracker
 *
 * @author Tobias Nyholm
 *
 */
interface HttpClientInterface
{
    /**
     * Send a post request to the endpoint
     *
     * @param array $data
     *
     * @return bool
     */
    public function send(array $data = array());
}
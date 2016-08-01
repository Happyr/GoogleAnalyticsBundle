<?php

namespace Happyr\GoogleAnalyticsBundle\Http;

/**
 * Interface HttpClientInterface.
 */
interface HttpClientInterface
{
    /**
     * Send a post request to the endpoint.
     *
     * @param array $data
     *
     * @return bool
     */
    public function send(array $data = []);
}

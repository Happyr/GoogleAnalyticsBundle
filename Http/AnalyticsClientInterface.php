<?php

namespace Happyr\GoogleAnalyticsBundle\Http;

interface AnalyticsClientInterface
{
    /**
     * Send a post request to the endpoint.
     *
     * @param array $data
     */
    public function send(array $data = []): bool;
}

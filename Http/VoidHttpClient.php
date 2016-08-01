<?php

namespace Happyr\GoogleAnalyticsBundle\Http;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class VoidHttpClient implements HttpClientInterface
{
    /**
     * This is just a dummy client.. Do nothing.
     *
     * @param array $data
     *
     * @return bool
     */
    public function send(array $data = [])
    {
        return true;
    }
}

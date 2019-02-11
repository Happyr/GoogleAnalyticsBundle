<?php

namespace Happyr\GoogleAnalyticsBundle\Http;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class VoidHttpClient implements AnalyticsClientInterface
{
    /**
     * This is just a dummy client.. Do nothing.
     */
    public function send(array $data = []): bool
    {
        return true;
    }
}

<?php

namespace Happyr\Google\AnalyticsBundle\Http;

/**
 * Class Dummy
 *
 * @author Tobias Nyholm
 *
 */
class Dummy implements HttpClientInterface
{
    /**
     * This is just a dummy client.. Do nothing
     *
     * @param array $data
     *
     * @return bool
     */
    public function send(array $data = array())
    {
        return true;
    }
}
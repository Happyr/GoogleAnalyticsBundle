<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Functional\Fixture;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class MessageFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface
    {

    }

}

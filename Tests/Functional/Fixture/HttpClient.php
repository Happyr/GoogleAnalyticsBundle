<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Functional\Fixture;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
    }
}

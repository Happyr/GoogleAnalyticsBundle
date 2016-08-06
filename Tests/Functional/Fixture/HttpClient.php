<?php

namespace Tests\Functional\Fixture;

use Psr\Http\Message\RequestInterface;

class HttpClient implements \Http\Client\HttpClient
{
    public function sendRequest(RequestInterface $request)
    {
    }
}

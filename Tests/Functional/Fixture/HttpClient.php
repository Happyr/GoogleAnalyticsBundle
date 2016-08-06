<?php

namespace Tests\Functional\Fixture;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements \Http\Client\HttpClient
{
    public function sendRequest(RequestInterface $request)
    {

    }
}
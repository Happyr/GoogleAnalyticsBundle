<?php

namespace Tests\Functional\Fixture;

class MessageFactory implements \Http\Message\MessageFactory
{
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        // TODO: Implement createRequest() method.
    }

    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        // TODO: Implement createResponse() method.
    }
}

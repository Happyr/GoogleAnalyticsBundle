<?php

namespace Happyr\GoogleAnalyticsBundle\Http;

use Http\Client\HttpClient as HttplugClient;
use Http\Message\MessageFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * This is an adapter for Httplug.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class HttpClient implements AnalyticsClientInterface
{
    /**
     * @var string endpoint
     *
     * Where to POST the requests
     */
    private $endpoint;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;


    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory, string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Send a post request to the endpoint.
     */
    public function send(array $data = []): bool
    {
        $request = $this->requestFactory->createRequest('POST', $this->endpoint);
        $request = $request->withAddedHeader('User-Agent', 'happyr-google-analytics/5.0');
        $request->getBody()->write(http_build_query($data));
        $response = $this->client->sendRequest($request);

        return $response->getStatusCode() === 200;
    }
}

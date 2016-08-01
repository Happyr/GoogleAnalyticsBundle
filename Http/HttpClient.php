<?php

namespace Happyr\GoogleAnalyticsBundle\Http;

use Http\Client\HttpClient as HttplugClient;
use Http\Message\MessageFactory;

/**
 * This is an adapter for Httplug.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var string endpoint
     *
     * Where to POST the requests
     */
    protected $endpoint;

    /**
     * @var HttplugClient client
     */
    protected $client;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @param HttplugClient  $client
     * @param MessageFactory $messageFactory
     * @param string         $endpoint
     */
    public function __construct(HttplugClient $client, MessageFactory $messageFactory, $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Send a post request to the endpoint.
     *
     * @param array $data
     *
     * @return bool
     */
    public function send(array $data = [])
    {
        $request = $this->getMessageFactory()->createRequest(
            'POST',
            $this->endpoint,
            ['User-Agent' => 'happyr-google-analytics/4.0'],
            http_build_query($data)
        );
        $response = $this->getClient()->sendRequest($request);

        return $response->getStatusCode() === 200;
    }

    /**
     * @return HttplugClient
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @return MessageFactory
     */
    protected function getMessageFactory()
    {
        return $this->messageFactory;
    }
}

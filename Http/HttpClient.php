<?php


namespace Happyr\Google\AnalyticsBundle\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Happyr\Google\AnalyticsBundle\Http\HttpClientInterface;

/**
 * Class Tracker
 *
 * @author Tobias Nyholm
 *
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
     * @var integer requestTimeout
     *
     */
    protected $requestTimeout;

    /**
     * @var boolean fireAndForget
     *
     * Should we bother about the response or not?
     */
    protected $fireAndForget;

    /**
     * @var Client client
     *
     */
    protected $client;

    /**
     * @param string $endpoint
     * @param boolean $fireAndForget
     * @param integer $requestTimeout
     */
    public function __construct($endpoint, $fireAndForget, $requestTimeout)
    {
        $this->endpoint = $endpoint;
        $this->fireAndForget = $fireAndForget;
        $this->requestTimeout = $requestTimeout;
    }

    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * Send a post request to the endpoint
     *
     * @param array $data
     *
     * @return bool
     */
    public function send(array $data = array())
    {
        $client = $this->getClient();
        $options = array(
            'body' => $data,
            'timeout' => $this->requestTimeout,
        );
        $request = $client->createRequest('POST', $this->endpoint, $options);

        // If we should send the async or not.
        if ($this->fireAndForget) {
            $client->sendAll(array($request));

            return true;
        }

        try {
            $response = $client->send($request);
        } catch (RequestException $e) {
            return false;
        }

        return $response->getStatusCode() == '200';
    }
}
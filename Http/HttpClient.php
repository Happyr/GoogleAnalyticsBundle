<?php


namespace Happyr\Google\AnalyticsBundle\Http;

use GuzzleHttp\Client;
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
     * @var Client client
     *
     */
    protected $client;

    /**
     * @param string $endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
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
    public function send(array $data=array())
    {
        $response=$this->getClient()->post($this->endpoint, $data);

        return true;
    }
}
<?php

namespace Happyr\Google\AnalyticsBundle\Service;

use Happyr\Google\AnalyticsBundle\Http\HttpClientInterface;

/**
 * Class Tracker
 *
 * @author Tobias Nyholm
 *
 */
class Tracker
{
    /**
     * @var string version
     *
     * The version of the Measurement Protocol
     */
    protected $version;

    /**
     * @var string trackerId
     *
     * The tracker id UA-XXXX-Y
     */
    protected $trackerId;

    /**
     * @var string clientId
     *
     * The unique client Id. This is a visitor id.
     */
    protected $clientId;

    /**
     * @var HttpClientInterface client
     *
     */
    protected $client;

    /**
     * @param HttpClientInterface $client
     * @param string $trackerId
     * @param string $version
     */
    public function __construct(HttpClientInterface $client, $trackerId, $version)
    {
        $this->client = $client;
        $this->trackerId = $trackerId;
        $this->version = $version;
    }

    /**
     * Send data to the google analytics API
     *
     * @param array $data
     * @param string $hitType This can also be included in the $data['t']
     *
     * @return bool
     */
    public function send($data, $hitType=null)
    {
        if (empty($data['t']) && $hitType!==null) {
            $data['t'] = $hitType;
        }

        $this->appendDefaultParameters($data);

        return $this->client->send($data);
    }


    protected function getClientId()
    {
        return 'abc';
    }

    /**
     * Append the default parameters
     *
     * @param array $data
     *
     */
    protected function appendDefaultParameters(array &$data)
    {
        if (empty($data['v'])) {
            $data['v'] = $this->version;
        }

        if (empty($data['tid'])) {
            $data['tid'] = $this->trackerId;
        }

        if (empty($data['cid'])) {
            $data['cid'] = $this->getClientId();
        }
    }
}
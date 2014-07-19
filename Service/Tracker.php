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
     * @var ClientIdProvider clientIdProvider
     *
     */
    protected $clientIdProvider;

    /**
     * @param HttpClientInterface $client
     * @param ClientIdProvider $cip
     * @param string $trackerId
     * @param string $version
     */
    public function __construct(HttpClientInterface $client, ClientIdProvider $cip, $trackerId, $version)
    {
        $this->client = $client;
        $this->trackerId = $trackerId;
        $this->version = $version;
        $this->clientIdProvider = $cip;
    }

    /**
     * Send data to the google analytics API
     *
     * @param array $data
     * @param string $hitType This can also be included in the $data['t']
     *
     * @return bool
     */
    public function send($data, $hitType = null)
    {
        if (empty($data['t']) && $hitType !== null) {
            $data['t'] = $hitType;
        }

        $this->appendDefaultParameters($data);

        return $this->client->send($data);
    }

    /**
     * Get client id from cookie... if we can
     *
     *
     * @return false|mixed|string
     */
    protected function getClientId()
    {
        if ($this->clientId == null) {
            //Try to get new client id from cookie
            if (false == $this->clientId = $this->clientIdProvider->getClientIdFormCookie()) {

                /*
                 * We could not find any cookie with a client id. We just have to randomize one
                 */
                $this->clientId = mt_rand(10, 1000).round(microtime(true));
            }
        }

        return $this->clientId;
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
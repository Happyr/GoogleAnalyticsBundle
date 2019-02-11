<?php

namespace Happyr\GoogleAnalyticsBundle\Service;

use Happyr\GoogleAnalyticsBundle\Http\AnalyticsClientInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Tracker
{
    /**
     * @var string version
     *
     * The version of the Measurement Protocol
     */
    private $version;

    /**
     * @var string trackerId
     *
     * The tracker id UA-XXXX-Y
     */
    private $trackerId;

    /**
     * @var string clientId
     *
     * The unique client Id. This is a visitor id
     */
    private $clientId;

    /**
     * @var AnalyticsClientInterface client
     */
    private $client;

    /**
     * @var ClientIdProvider clientIdProvider
     */
    private $clientIdProvider;

    public function __construct(AnalyticsClientInterface $client, ClientIdProvider $cip, string $trackerId, string $version)
    {
        $this->client = $client;
        $this->trackerId = $trackerId;
        $this->version = $version;
        $this->clientIdProvider = $cip;
    }

    /**
     * Send data to the google analytics API.
     *
     * @param array  $data
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
     * Append the default parameters.
     *
     * @param array $data
     */
    private function appendDefaultParameters(array &$data)
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

    private function getClientId(): ?string
    {
        if ($this->clientId === null) {
            $this->clientId = $this->clientIdProvider->getClientId();
        }

        return $this->clientId;
    }
}

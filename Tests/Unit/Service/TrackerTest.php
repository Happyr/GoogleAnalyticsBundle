<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Unit\Service;

use Happyr\GoogleAnalyticsBundle\Http\HttpClientInterface;
use Happyr\GoogleAnalyticsBundle\Service\ClientIdProvider;
use Happyr\GoogleAnalyticsBundle\Service\Tracker;

/**
 * Class TrackerTest.
 *
 * @author Tobias Nyholm
 *
 * @internal
 */
final class TrackerTest extends \PHPUnit\Framework\TestCase
{
    public function testSend()
    {
        $trackerId = 'foo';
        $version = 1;
        $clientId = 'clientId';

        $data = [
            'v' => $version,
            'tid' => $trackerId,
            'cid' => $clientId,
            't' => 'pageview',
            'baz' => 'bar',
        ];

        $httpClient = $this->getMockBuilder(HttpClientInterface::class)
            ->setMethods(['send'])
            ->disableOriginalConstructor()
            ->getMock();

        $httpClient->expects(self::once())
            ->method('send')
            ->with($data)
            ->willReturn(true);

        $clientIdProvider = $this->getMockBuilder(ClientIdProvider::class)
            ->setMethods(['getClientId'])
            ->disableOriginalConstructor()
            ->getMock();

        $clientIdProvider->expects(self::once())
            ->method('getClientId')
            ->willReturn($clientId);

        $tracker = new Tracker($httpClient, $clientIdProvider, $trackerId, $version);

        $tracker->send(['baz' => 'bar'], 'pageview');
    }
}

<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Unit\Service;

use Happyr\GoogleAnalyticsBundle\Service\Tracker;

/**
 * Class TrackerTest.
 *
 * @author Tobias Nyholm
 */
class TrackerTest extends \PHPUnit_Framework_TestCase
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

        $httpClient = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Http\HttpClientInterface')
            ->setMethods(['send'])
            ->disableOriginalConstructor()
            ->getMock();

        $httpClient->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);

        $clientIdProvider = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Service\ClientIdProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $tracker = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Service\Tracker')
            ->setMethods(['getClientId'])
            ->enableOriginalConstructor()
            ->setConstructorArgs([$httpClient, $clientIdProvider, $trackerId, $version])
            ->getMock();

        $tracker->expects($this->once())
            ->method('getClientId')
            ->willReturn($clientId);

        $tracker->send(['baz' => 'bar'], 'pageview');
    }

    public function testGetClientId()
    {
        $clientId = 'foobar';
        $provider = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Service\ClientIdProvider')
            ->setMethods(['getClientId'])
            ->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())
            ->method('getClientId')
            ->willReturn($clientId);

        $tracker = new TrackerDummy(
            $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Http\HttpClientInterface')->getMock(),
            $provider,
            'foo',
            'bar'
        );

        $firstResult = $tracker->getClientId();
        $secondResult = $tracker->getClientId();

        $this->assertEquals($firstResult, $secondResult, 'getClientId must return the same ID at every call');
    }
}
class TrackerDummy extends Tracker
{
    public function getClientId()
    {
        return parent::getClientId();
    }
}

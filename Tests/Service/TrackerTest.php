<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Service;

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

        $data = array(
            'v' => $version,
            'tid' => $trackerId,
            'cid' => $clientId,
            't' => 'pageview',
            'baz' => 'bar',
        );

        $httpClient = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Http\HttpClientInterface')
            ->setMethods(array('send'))
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
            ->setMethods(array('getClientId'))
            ->enableOriginalConstructor()
            ->setConstructorArgs(array($httpClient, $clientIdProvider, $trackerId, $version))
            ->getMock();

        $tracker->expects($this->once())
            ->method('getClientId')
            ->willReturn($clientId);

        $tracker->send(array('baz' => 'bar'), 'pageview');
    }

    public function testGetClientId()
    {
        $clientId = 'foobar';
        $provider = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Service\ClientIdProvider')
            ->setMethods(array('getClientId'))
            ->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())
            ->method('getClientId')
            ->willReturn($clientId);

        $tracker = new TrackerDummy(
            $this->getMock('Happyr\GoogleAnalyticsBundle\Http\HttpClientInterface'),
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

<?php


namespace Happyr\Google\AnalyticsBundle\Tests\Service;

use Happyr\Google\AnalyticsBundle\Service\Tracker;

/**
 * Class TrackerTest
 *
 * @author Tobias Nyholm
 *
 *
 */
class TrackerTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $trackerId='foo';
        $version=1;
        $clientId='clientId';

        $data=array(
            'v'=>$version,
            'tid'=>$trackerId,
            'cid'=>$clientId,
            't'=>'pageview',
            'baz'=>'bar',
        );

        $httpClient=$this->getMockBuilder('Happyr\Google\AnalyticsBundle\Http\HttpClientInterface')
            ->setMethods(array('send'))
            ->disableOriginalConstructor()
            ->getMock();

        $httpClient->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);

        $clientIdProvider=$this->getMock('Happyr\Google\AnalyticsBundle\Service\ClientIdProvider');

        $tracker = $this->getMockBuilder('Happyr\Google\AnalyticsBundle\Service\Tracker')
            ->setMethods(array('getClientId'))
            ->enableOriginalConstructor()
            ->setConstructorArgs(array($httpClient, $clientIdProvider, $trackerId, $version))
            ->getMock();

        $tracker->expects($this->once())
            ->method('getClientId')
            ->willReturn($clientId);

        $tracker->send(array('baz'=>'bar'), 'pageview');
    }

    public function testGetClientId()
    {
        $provider = $this->getMockBuilder('Happyr\Google\AnalyticsBundle\Service\ClientIdProvider')
            ->setMethods(array('getClientIdFormCookie'))
            ->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())
            ->method('getClientIdFormCookie')
            ->willReturn(false);

        $tracker = new TrackerDummy(
            $this->getMock('Happyr\Google\AnalyticsBundle\Http\HttpClientInterface'),
            $provider,
            'foo',
            'bar'
        );

        $firstResult=$tracker->getClientId();
        $secondResult=$tracker->getClientId();

        $this->assertEquals($firstResult, $secondResult, 'getClientId must return the same ID at every call');
        $this->assertRegExp('|[0-9]{9}+|', $firstResult, 'Not big and random enough');

    }
}
class TrackerDummy extends Tracker
{
    public function getClientId()
    {
        return parent::getClientId();
    }
}
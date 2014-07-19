<?php


namespace Happyr\Google\AnalyticsBundle\Tests\Service;

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
}

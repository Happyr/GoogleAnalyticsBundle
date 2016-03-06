<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Http;

/**
 * Class HttpClientTest.
 *
 * @author Tobias Nyholm
 */
class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $endpoint = 'foobar';
        $data = array(
            'baz' => 'biz',
            'bax' => 'foo',
        );

        $request = $this->getMockBuilder('GuzzleHttp\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this->getMockBuilder('GuzzleHttp\Message\Response')
            ->setMethods(array('getStatusCode'))
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn('200');

        $guzzleClient = $this->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(array('createRequest', 'send'))
            ->disableOriginalConstructor()
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('createRequest')
            ->with($this->equalTo('POST'), $this->equalTo($endpoint), $this->callback(function ($param) use ($data) {
                //make sure the data is in the body post
                foreach ($data as $k => $v) {
                    if (!isset($param['body'][$k]) || $param['body'][$k] !== $v) {
                        return false;
                    }
                }

                return true;
            }))
            ->willReturn($request);
        $guzzleClient->expects($this->once())
            ->method('send')
            ->with($this->equalTo($request))
            ->willReturn($response);

        $httpClient = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Http\HttpClient')
            ->setMethods(array('getClient'))
            ->setConstructorArgs(array($endpoint, false, 1))
            ->getMock();
        $httpClient->expects($this->once())
            ->method('getClient')
            ->willReturn($guzzleClient);

        $httpClient->send($data);
    }
}

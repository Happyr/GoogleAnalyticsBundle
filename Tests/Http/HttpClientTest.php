<?php

namespace Happyr\Google\AnalyticsBundle\Tests\Http;

/**
 * Class HttpClientTest
 *
 * @author Tobias Nyholm
 *
 */
class HttpClientTest extends \PHPUnit_Framework_TestCase
{

    public function testSend()
    {
        $endpoint='foobar';
        $data=array(
            'baz'=>'biz',
            'bax'=>'foo',
        );
        $response=$this->getMockBuilder('GuzzleHttp\Message\Response')
            ->setMethods(array('getStatusCode'))
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn('200');

        $guzzleClient=$this->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(array('post'))
            ->disableOriginalConstructor()
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('post')
            ->with($this->equalTo($endpoint), $this->callback(function ($param) use ($data) {
                //make sure the data is in the body post
                foreach($data as $k=>$v) {
                    if(!isset($param['body'][$k]) || $param['body'][$k]!==$v) {
                        return false;
                    }
                }
                return true;
            }))
            ->willReturn($response);

        $httpClient=$this->getMockBuilder('Happyr\Google\AnalyticsBundle\Http\HttpClient')
            ->setMethods(array('getClient'))
            ->setConstructorArgs(array($endpoint))
            ->getMock();
        $httpClient->expects($this->once())
            ->method('getClient')
            ->willReturn($guzzleClient);

        $httpClient->send($data);
    }
}
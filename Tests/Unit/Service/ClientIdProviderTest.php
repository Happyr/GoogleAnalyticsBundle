<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Unit\Service;

use Happyr\GoogleAnalyticsBundle\Service\ClientIdProvider;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ClientIdProviderTest.
 *
 * @author Tobias Nyholm
 */
class ClientIdProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetClientId()
    {
        $provider = $this->getMockBuilder('Happyr\GoogleAnalyticsBundle\Service\ClientIdProvider')
            ->setMethods(['getClientIdFormCookie'])
            ->disableOriginalConstructor()
            ->getMock();
        $provider->expects($this->once())
            ->method('getClientIdFormCookie')
            ->willReturn(false);

        $result = $provider->getClientId();

        $this->assertRegExp('|[0-9]{9}+|', $result, 'Not big and random enough');
    }

    public function testExtractCookie()
    {
        $provider = new ProviderDummy();

        $this->assertEquals('1110480476.1405690517', $provider->extractCookie('GA1.2.1110480476.1405690517'));
        $this->assertEquals('286403989.1366364567', $provider->extractCookie('1.2.286403989.1366364567'));
    }
}
class ProviderDummy extends ClientIdProvider
{
    public function __construct()
    {
        $this->requestStack = new RequestStack();
    }

    public function extractCookie($cookieValue)
    {
        return parent::extractCookie($cookieValue);
    }
}

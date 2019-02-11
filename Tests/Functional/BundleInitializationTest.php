<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Functional;

use Happyr\GoogleAnalyticsBundle\HappyrGoogleAnalyticsBundle;
use Happyr\GoogleAnalyticsBundle\Service\Tracker;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;

/**
 * @internal
 */
final class BundleInitializationTest extends BaseBundleTestCase
{
    protected function getBundleClass()
    {
        return HappyrGoogleAnalyticsBundle::class;
    }

    protected function setUp()
    {
        parent::setUp();

        // Make services public that have an idea that matches a regex
        $this->addCompilerPass(new PublicServicePass('|Happyr.*|'));
    }

    public function testInitBundle()
    {
        $kernel = $this->createKernel();
        // Add some configuration
        $kernel->addConfigFile(__DIR__.'/config/default.yml');

        // Boot the kernel.
        $this->bootKernel();

        // Get the container
        $container = $this->getContainer();

        // Test if you services exists
        $this->assertTrue($container->has(Tracker::class));
        $service = $container->get(Tracker::class);
        $this->assertInstanceOf(Tracker::class, $service);
    }
}

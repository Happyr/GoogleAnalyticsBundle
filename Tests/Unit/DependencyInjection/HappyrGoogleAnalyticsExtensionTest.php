<?php

namespace Tests\Unit\DependencyInjection;

use Happyr\GoogleAnalyticsBundle\DependencyInjection\HappyrGoogleAnalyticsExtension;
use Happyr\GoogleAnalyticsBundle\Service\DataFetcher;
use Happyr\GoogleAnalyticsBundle\Service\Tracker;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Reference;

class HappyrGoogleAnalyticsExtensionTest extends AbstractExtensionTestCase
{
    protected function getMinimalConfiguration()
    {
        return ['tracking_id' => 'id'];
    }

    protected function getContainerExtensions()
    {
        return [
            new HappyrGoogleAnalyticsExtension(),
        ];
    }

    public function testTracker()
    {
        $this->load();
        $this->assertContainerBuilderHasService('happyr.google_analytics.tracker', Tracker::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('happyr.google_analytics.tracker', 0, new Reference('happyr.google_analytics.http.client'));
    }

    public function testTrackerDisabled()
    {
        $this->load(['enabled' => false]);
        $this->assertContainerBuilderHasService('happyr.google_analytics.tracker', Tracker::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('happyr.google_analytics.tracker', 0, new Reference('happyr.google_analytics.http.void'));
    }

    public function testFetcher()
    {
        $this->load(['fetching' => ['client_service' => 'foo']]);
        $this->assertContainerBuilderHasService('happyr.google_analytics.data_fetcher', DataFetcher::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('happyr.google_analytics.data_fetcher', 1, new Reference('foo'));
    }

    public function testFetcherDisabled()
    {
        $this->load();
        $this->assertContainerBuilderNotHasService('happyr.google_analytics.data_fetcher');
    }
}

<?php

namespace Happyr\GoogleAnalyticsBundle\DependencyInjection;

use Cache\Adapter\Void\VoidCachePool;
use Happyr\GoogleAnalyticsBundle\Http\HttpClient;
use Happyr\GoogleAnalyticsBundle\Service\DataFetcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HappyrGoogleAnalyticsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $trackerDef = $container->getDefinition('happyr.google_analytics.tracker');
        $trackerDef->replaceArgument(2, $config['tracking_id'])
            ->replaceArgument(3, $config['version']);

        if (!$config['enabled']) {
            $trackerDef->replaceArgument(0, new Reference('happyr.google_analytics.http.void'));
        } else {
            $container->register('happyr.google_analytics.http.client', HttpClient::class)
                ->setPublic(false)
                ->addArgument(new Reference($config['http_client']))
                ->addArgument(new Reference($config['http_message_factory']))
                ->addArgument($config['endpoint']);
        }

        if (!empty($config['fetching']['client_service'])) {
            $this->configureDataFetcher($container, $config);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param $config
     */
    private function configureDataFetcher(ContainerBuilder $container, $config)
    {
        if (!empty($config['fetching']['cache_service'])) {
            $cacheService = $config['fetching']['cache_service'];
        } else {
            $cacheService = 'happyr.google_analytics.cache.void';
            $container->register($cacheService, VoidCachePool::class)
                ->setPublic(false);
        }

        $container->register('happyr.google_analytics.data_fetcher', DataFetcher::class)
            ->addArgument(new Reference($cacheService))
            ->addArgument(new Reference($config['fetching']['client_service']))
            ->addArgument($config['fetching']['view_id'])
            ->addArgument($config['fetching']['cache_lifetime']);
    }
}

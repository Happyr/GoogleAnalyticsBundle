<?php

namespace Happyr\GoogleAnalyticsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

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

        $base = 'happyr.google_analytics.param.';
        $container->setParameter($base.'endpoint', $config['endpoint']);
        $container->setParameter($base.'view_id', $config['fetching']['view_id']);
        $container->setParameter($base.'cache_lifetime', $config['fetching']['cache_lifetime']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $trackerDef = $container->getDefinition('happyr.google_analytics.tracker');
        $trackerDef->replaceArgument(2, $config['tracking_id'])
            ->replaceArgument(3, $config['version']);

        if (!$config['enabled']) {
            $trackerDef->replaceArgument(0, new Reference('happyr.google_analytics.http.void'));
        } else {
            $container->getDefinition('happyr.google_analytics.http.client')
                ->replaceArgument(0, new Reference($config['http_client']))
                ->replaceArgument(1, new Reference($config['http_message_factory']));
        }

        if ($config['fetching']['cache_service']) {
            $container->getDefinition('happyr.google_analytics.data_fetcher')
                ->replaceArgument(0, new Reference($config['fetching']['cache_service']));
        }

        if ($config['fetching']['client_service']) {
            $container->getDefinition('happyr.google_analytics.data_fetcher')
                ->replaceArgument(1, new Reference($config['fetching']['client_service']));
        } else {
            $container->removeDefinition('happyr.google_analytics.data_fetcher');
        }
    }
}

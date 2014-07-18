<?php

namespace Happyr\Google\AnalyticsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HappyrGoogleAnalyticsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->getDefinition('happyr.google.analytics.http.client')
            ->replaceArgument(0, $config['endpoint']);

        $container->getDefinition('happyr.google.analytics.tracker')
            ->replaceArgument(1, $config['tracker_id'])
            ->replaceArgument(2, $config['version']);

        if (!$config['enabled']) {
            $container->getDefinition('happyr.google.analytics.tracker')
                ->replaceArgument(0, new Reference('happyr.google.analytics.http.dummy'));
        }
    }
}

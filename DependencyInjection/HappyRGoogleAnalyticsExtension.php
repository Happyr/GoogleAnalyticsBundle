<?php

namespace HappyR\Google\AnalyticsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HappyRGoogleAnalyticsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('happy_r_google_analytics', $config);
        $container->setParameter('happy_r_google_analytics.token_file_path', $config['token_file_path']);
        $container->setParameter('happy_r_google_analytics.tracker_id', $config['tracker_id']);
        $container->setParameter('happy_r_google_analytics.host', $config['host']);

        //if tracker is not enabled, use the dummy
        if(!$config['tracker_enabled']){
            $container->setParameter('happyr.google.analytics.tracker.class', 'HappyR\Google\AnalyticsBundle\Services\TrackerDummyService');
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}

<?php

namespace HappyR\Google\AnalyticsBundle\DependencyInjection;

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
        $container->setParameter('happy_r_google_analytics.host', $config['host']);

        $container->setParameter('happy_r_google_analytics.tracker_id', $config['tracker_id']);
        $container->setParameter('happy_r_google_analytics.tracker', $config['tracker']);

        //if tracker is not enabled, use the dummy
        if(!$config['tracker_enabled']){
            $container->setParameter('happyr.google.analytics.tracker.class',
                'HappyR\Google\AnalyticsBundle\Services\TrackerDummyService');
        }

        switch($config['cache']['service']){
            case 'doctrine':
                if (!isset($config['cache']['doctrine_class'])){
                    throw new \LogicException('When using the "doctrine" as cache.service, the "cache.doctrine_class" config parameter must be set.');
                }

                //check if doctrine exists
                if(!class_exists($config['cache']['doctrine_class'])){
                    throw new \LogicException('The class "'.$config['cache']['doctrine_class'].'" does not exist.');
                }

                $container->setParameter('happyr.google.analytics.cache.doctrine_class', $config['cache']['doctrine_class']);

                $cacheService = new Reference('happyr.google.analytics.cache.doctrine');
                break;
            default:
                $cacheService = new Reference($config['cache']['service']);


        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->getDefinition('happyr.google.analytics.page_statistics')
            ->replaceArgument(2, $cacheService);


    }
}

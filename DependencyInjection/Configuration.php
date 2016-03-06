<?php

namespace Happyr\GoogleAnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('happyr_google_analytics');

        $rootNode
            ->children()
            ->scalarNode('version')->cannotBeEmpty()->defaultValue(1)->end()
            ->booleanNode('enabled')->defaultTrue()->end()
            ->scalarNode('tracking_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('endpoint')->defaultValue('http://www.google-analytics.com/collect')->cannotBeEmpty()->end()
            ->booleanNode('fireAndForget')->defaultFalse()->end()
            ->floatNode('requestTimeout')->defaultValue(1)->end()
            ->arrayNode('fetching')->addDefaultsIfNotSet()->children()
                ->integerNode('view_id')->defaultNull()->info('The google analytics view id. This is not the same as the tracking code.')->end()
                ->scalarNode('cache_service')->defaultNull()->end()
                ->integerNode('cache_lifetime')->defaultValue(3600)->end()
                ->scalarNode('client_service')->defaultNull()->end()
            ->end()
            ->end()->end();

        return $treeBuilder;
    }
}

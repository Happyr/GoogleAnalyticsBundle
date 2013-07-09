<?php

namespace HappyR\Google\AnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('happy_r_google_analytics');

         $rootNode
          ->children()
            ->booleanNode('tracker_enabled')->defaultTrue()->end()
            ->scalarNode('profile_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('tracker_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('token_file_path')->isRequired()->cannotBeEmpty()->end()
          ->end()->end();

        return $treeBuilder;
    }
}

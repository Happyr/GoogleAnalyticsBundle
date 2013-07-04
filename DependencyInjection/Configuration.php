<?php

namespace HappyR\Google\AnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
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
            ->scalarNode('profile_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('token_file')->isRequired()->cannotBeEmpty()->end()
          ->end()->end();

        return $treeBuilder;
    }
}

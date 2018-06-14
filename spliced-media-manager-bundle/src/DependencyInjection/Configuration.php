<?php

namespace Spliced\Bundle\MediaManagerBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('spliced_media_manager');

		$rootNode->
		    children()
		        ->scalarNode('layout')
		            ->isRequired()
		            ->cannotBeEmpty()
		        ->end()
		        ->scalarNode('default_repository')
		            ->isRequired()
		            ->cannotBeEmpty()
		        ->end()
		        ->scalarNode('cache_dir')
		            ->isRequired()
		            ->cannotBeEmpty()
		        ->end()
		        ->arrayNode('repositories')
		            ->requiresAtLeastOneElement()
		            ->prototype('array')
		                ->children()
		                    ->scalarNode('name')
		                        ->isRequired()
								->cannotBeEmpty()
		                    ->end()
		                    ->scalarNode('path')
		                        ->isRequired()
								->cannotBeEmpty()
		                    ->end()
		                    ->scalarNode('web_path')
		                        ->isRequired()
								->cannotBeEmpty()
		                    ->end()
			                ->scalarNode('type')
		                        ->isRequired()
								->cannotBeEmpty()
		                    ->end()
		                ->end()
		            ->end()
		        ->end()
		    ->end()
		;

        return $treeBuilder;
    }
}

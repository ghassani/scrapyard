<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('spliced_cms');

        $rootNode
        	->children()
        		->booleanNode('debug')
        			->defaultTrue()
        		->end()
        		->scalarNode('connection')
        			->defaultValue('default')
        		->end()
                ->scalarNode('user_provider')
                    ->defaultValue('fos_user.user_manager')
                ->end()
        		->scalarNode('admin_route_prefix')
        			->defaultValue('admin')
        		->end()
        	->end();

        return $treeBuilder;
    }
}

<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * BaseConfiguration
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class BaseConfiguration
{
    /**
     * buildConfigurationTree
     * 
     * @param bool $admin - defaults to false
     */
    protected function buildConfigurationTree($admin = false)
    {
        $treeBuilder = new TreeBuilder();
        
        $rootNode = $treeBuilder->root($admin ? 'spliced_commerce_admin' : 'spliced_commerce');
                
        $rootNode
               ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('document_manager')
                    ->defaultValue('doctrine_mongodb.odm.document_manager')
                ->end()
                ->scalarNode('entity_manager')
                    ->defaultValue('doctrine.orm.entity_manager')
                ->end()
                
            ->end()
        ->end();
        
        return $treeBuilder;
    }
}

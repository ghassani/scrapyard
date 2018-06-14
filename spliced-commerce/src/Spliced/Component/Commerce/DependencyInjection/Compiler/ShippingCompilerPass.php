<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * ShippingCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShippingCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.shipping_manager')) {
            return;
        }

        $shippingManager = $container->getDefinition('commerce.shipping_manager');

        
        foreach($container->findTaggedServiceIds('commerce.shipping_method') as $id => $attributes){
            $shippingManager->addMethodCall('addMethod', array(new Reference($id)));
        }
        
        foreach($container->findTaggedServiceIds('commerce.shipping_provider') as $id => $attributes){
            $shippingManager->addMethodCall('addProvider', array(new Reference($id)));
        }
             
        
    }
}
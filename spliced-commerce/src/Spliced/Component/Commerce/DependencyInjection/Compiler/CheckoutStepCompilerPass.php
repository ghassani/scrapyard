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
 * CheckoutStepCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutStepCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.checkout_manager')) {
            return;
        }
    
        $checkoutManager = $container->getDefinition('commerce.checkout_manager');

        // add each step that has been tagged as a checkout step handler
        $i = 1; // keep a counter for services which have not been tagged a step, add them sequentially
        foreach($container->findTaggedServiceIds('commerce.checkout_step') as $id => $attributes){
            $step = isset($attributes[0]['step']) ? $attributes[0]['step'] : $i;
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 10;
            
            $checkoutManager->addMethodCall('addStepHandler', array(new Reference($id)));
     
            $container->getDefinition($id)->addMethodCall('setStep', array($step))
              ->addMethodCall('setPriority', array($priority));
            
            ++$i;
        } 
        
        // now we validate the steps added to make sure they 
        // are secuential from 1 to the last registered step number
        $checkoutManager->addMethodCall('validateStepHandlers');
     
    }
}
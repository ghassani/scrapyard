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
 * PaymentCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PaymentCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.payment_manager')) {
            return;
        }

        $paymentManager = $container->getDefinition('commerce.payment_manager');

        
        foreach($container->findTaggedServiceIds('commerce.payment_provider') as $id => $attributes){
            $paymentManager->addMethodCall('addProvider', array(new Reference($id)));
        }
        
    }
}
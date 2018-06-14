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
 * ProductTypeCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.product_type_manager')) {
            return;
        }

        $productTypeManager = $container->getDefinition('commerce.product_type_manager');

        foreach($container->findTaggedServiceIds('commerce.product_type') as $id => $attributes){
            $productTypeManager->addMethodCall('addType', array(new Reference($id)));
        }

    }
}
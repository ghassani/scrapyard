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
 * AnalyticsCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AnalyticsCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.analytics_manager')) {
            return;
        }

        $analyticsManager = $container->getDefinition('commerce.analytics_manager');

        $services = $container->findTaggedServiceIds('commerce.analytics_subscriber');
        foreach ($services as $id => $attributes) {
            $analyticsManager->addMethodCall('addSubscriber', array(new Reference($id)));
        }
    }
}
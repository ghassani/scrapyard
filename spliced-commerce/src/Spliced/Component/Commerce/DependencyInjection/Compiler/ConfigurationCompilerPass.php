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
 * ConfigurationCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.configuration') && !$container->hasDefinition('commerce.admin.configuration')) {
            return;
        }
        
        if ($container->hasDefinition('commerce.configuration')) {
            $configurationInitializer = $container->getDefinition('commerce.configuration');
        } else {
            $configurationInitializer = $container->getDefinition('commerce.admin.configuration');
        }
        
        
        // add all our services which have configuration definitions
        foreach ($container->findTaggedServiceIds('commerce.configurable') as $id => $attributes) {
            $configurationInitializer->addMethodCall('addConfigurableServiceId', array($id));
        }

        // add all our configuration field types
        foreach ($container->findTaggedServiceIds('commerce.configuration_type') as $id => $attributes) {
            $configurationInitializer->addMethodCall('addFieldType', array(new Reference($id))); 
        }
    }
}
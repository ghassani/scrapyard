<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * TypeCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('spliced.configuration_manager')) {
            return;
        }
        $configurationManager = $container->getDefinition('spliced.configuration_manager');
        foreach($container->findTaggedServiceIds('spliced.configuration_type') as $id => $attributes){
            $configurationManager->addMethodCall('addType', array(new Reference($id)));
        }
    }
}
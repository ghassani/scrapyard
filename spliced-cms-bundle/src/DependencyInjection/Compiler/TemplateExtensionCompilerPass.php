<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Reference;

/**
 * TemplateExtensionCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TemplateExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('spliced_cms.template_manager')) {
            return;
        }
        $templateManager = $container->getDefinition('spliced_cms.template_manager');
        $i = 1;
        foreach($container->findTaggedServiceIds('spliced_cms.template_extension') as $id => $attributes){
            $templateManager->addMethodCall('addExtension', array(new Reference($id)));
            ++$i;
        }
    }
}
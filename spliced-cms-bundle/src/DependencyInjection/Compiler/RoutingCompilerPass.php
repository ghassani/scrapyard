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

/**
 * RoutingCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * @author https://github.com/BeSimple/BeSimpleI18nRoutingBundle/blob/master/DependencyInjection/Compiler/OverrideRoutingCompilerPass.php
 */
class RoutingCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('spliced_cms.router')) {
            return;
        }
        if ($container->hasAlias('router')) {
            // router is an alias.
            $container->setAlias('spliced_cms.router.parent', new Alias((string) $container->getAlias('router'), false));
        } else {
            // router is a definition.
            // Register it again as a private service to inject it as the parent
            $definition = $container->getDefinition('router');
            $definition->setPublic(false);
            $container->setDefinition('spliced_cms.router.parent', $definition);
        }
        $container->setDefinition('router', $container->getDefinition('spliced_cms.router'));
    }
}
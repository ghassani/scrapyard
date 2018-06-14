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
        if (!$container->hasDefinition('commerce.router')) {
            return;
        }

        if ($container->hasAlias('router')) {
            // router is an alias.
            $container->setAlias('commerce.router.parent', new Alias((string) $container->getAlias('router'), false));
        } else {
            // router is a definition.
            // Register it again as a private service to inject it as the parent
            $definition = $container->getDefinition('router');
            $definition->setPublic(false);
            $container->setDefinition('commerce.router.parent', $definition);
        }

        $container->setDefinition('router', $container->getDefinition('commerce.router'));
    }
}
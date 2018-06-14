<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;

/**
 * CustomerFactory
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerFactory extends FormLoginFactory
{
    
    /**
     * {@inheritDoc}
     */
    protected function getListenerId()
    {
        return 'commerce.security.authentication.listener.customer';
    }
    
    /**
     * {@inheritDoc}
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'commerce.security.authentication.provider.customer.'.$id;
        
        $container
            ->setDefinition($provider, new DefinitionDecorator('commerce.security.authentication.provider.customer'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, new Reference($userProviderId))
        ;
        return $provider;
    }

    /**
     * {@inheritDoc}
     */
    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'commerce.security.authentication.entry_point.customer.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('commerce.security.authentication.entry_point.customer'))
            ->addArgument(new Reference('security.http_utils'))
            ->addArgument($config['login_path'])
            ->addArgument($config['use_forward'])
        ;

        return $entryPointId;
    }
}

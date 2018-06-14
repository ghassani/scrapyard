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

/**
 * FacebookFactory
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FacebookFactory extends AbstractFactory
{
    public function __construct()
    {
        $this->addOption('display', 'page');
        $this->addOption('app_url');
        $this->addOption('server_url');
        $this->addOption('create_user_if_not_exists', false);
        $this->addOption('redirect_to_facebook_login', true);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'facebook_login';
    }

    protected function getListenerId()
    {
        return 'commerce.security.authentication.listener.facebook';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {

        $authProviderId = 'commerce.security.authentication.provider.facebook.'.$id;
        
        $definition = $container
            ->setDefinition($authProviderId, new DefinitionDecorator('commerce.security.authentication.provider.facebook'))
            ->replaceArgument(0, $id);

        // with user provider
        if (isset($config['provider'])) {
            $definition
            ->addArgument(new Reference($userProviderId))
            ->addArgument(new Reference('security.user_checker'))
            ->addArgument($config['create_user_if_not_exists'])
            ;
        }

        return $authProviderId;
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPointId)
    {
        $entryPointId = 'commerce.security.authentication.entry_point.facebook.'.$id;

        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('commerce.security.authentication.entry_point.facebook'))
            ->replaceArgument(1, $config);

        return $entryPointId;
    }
}

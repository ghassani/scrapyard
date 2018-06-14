<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * SplicedCommerceExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SplicedCommerceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $xmlLoader->load('configuration.xml');
        $xmlLoader->load('configuration_field_types.xml');
        $xmlLoader->load('services.xml');
        $xmlLoader->load('routing.xml');
        $xmlLoader->load('twig.xml');
        $xmlLoader->load('events.xml');
        $xmlLoader->load('controllers.xml');
        $xmlLoader->load('forms.xml');
        $xmlLoader->load('security.xml');
        $xmlLoader->load('shipping.xml');
        $xmlLoader->load('payment.xml');
        $xmlLoader->load('analytics.xml');
        $xmlLoader->load('security_facebook.xml');
        $xmlLoader->load('security_google.xml');
        $xmlLoader->load('security_yahoo.xml');
        $xmlLoader->load('security_twitter.xml');
        $xmlLoader->load('security_paypal.xml');
        $xmlLoader->load('checkout.xml');
        $xmlLoader->load('product_types.xml');

        
        $container->setParameter('commerce.checkout_notifiers', array());
        $container->setAlias('commerce.entity_manager', $config['entity_manager']);
        $container->setAlias('commerce.document_manager', $config['document_manager']);
    }
}

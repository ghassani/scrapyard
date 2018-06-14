<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Spliced\Component\Commerce\DependencyInjection\Security\Factory\CustomerFactory;
use Spliced\Component\Commerce\DependencyInjection\Security\Factory\FacebookFactory;
use Spliced\Component\Commerce\DependencyInjection\Security\Factory\TwitterFactory;
use Spliced\Component\Commerce\DependencyInjection\Security\Factory\GoogleFactory;
use Spliced\Component\Commerce\DependencyInjection\Security\Factory\PayPalFactory;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ConfigurationCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ShippingCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\PaymentCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\CheckoutNotifierCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\RoutingCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ProductTypeCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\CheckoutStepCompilerPass;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * SplicedCommerceBundle
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SplicedCommerceBundle extends Bundle
{
    /**
     * Constructor
     */
    public function __construct()
    {
        Type::registerType('configuration_value', 'Spliced\Component\Commerce\Doctrine\ODM\MongoDB\Types\ConfigurationValueType');
    }
    
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** Add Support for All Login Types */
        $security = $container->getExtension('security');
        $security->addSecurityListenerFactory(new CustomerFactory());
        $security->addSecurityListenerFactory(new FacebookFactory());
        $security->addSecurityListenerFactory(new TwitterFactory());
        $security->addSecurityListenerFactory(new GoogleFactory());
        $security->addSecurityListenerFactory(new PayPalFactory());
        
        /** Add Support For Custom Tags for Services */
        $container->addCompilerPass(new ShippingCompilerPass());
        $container->addCompilerPass(new PaymentCompilerPass());
        $container->addCompilerPass(new ConfigurationCompilerPass());
        $container->addCompilerPass(new CheckoutNotifierCompilerPass());
        $container->addCompilerPass(new RoutingCompilerPass());
        $container->addCompilerPass(new ProductTypeCompilerPass());
        $container->addCompilerPass(new CheckoutStepCompilerPass());
    }

}
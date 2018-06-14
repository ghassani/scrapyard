<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ConfigurationCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ShippingCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\PaymentCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ProductTypeCompilerPass;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * SplicedCommerceAdminBundle
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SplicedCommerceAdminBundle extends Bundle
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
    
        $container->addCompilerPass(new ShippingCompilerPass());
        $container->addCompilerPass(new PaymentCompilerPass());
        $container->addCompilerPass(new ConfigurationCompilerPass());
        $container->addCompilerPass(new ProductTypeCompilerPass());
    
    }
    
}
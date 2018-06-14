<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping\Model;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ShippingMethod
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ShippingMethod implements ShippingMethodInterface
{
    /** @var string */
    protected $name;
    
    /** @var ShippingProviderInterface */
    protected $provider;

    /**
     * Constructor
     * 
     * @param string $name
     * @param ShippingProviderInterface $provider
     */
    public function __construct($name, ShippingProviderInterface $provider)
    {
        $this->provider = $provider;
        $this->name = $name;
    }

    /**
     * toString
     */
     public function __toString()
     {
         return $this->getLabel();
     }

    /**
     * getProvider
     *
     * @return ShippingProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * getCartManager
     *
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->getProvider()->getCartManager();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getFullName
     * 
     * Returns the full name (provider name and method name)
     */
    public function getFullName()
    {
        return sprintf('%s_%s',$this->getProvider()->getName(), $this->getName());
    }
}

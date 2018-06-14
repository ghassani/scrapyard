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
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Shipping\ShippingMethodCollection;
use Spliced\Component\Commerce\Shipping\ShippingManager;

/**
 * ShippingProvider
 *
 * Use this class to get started creating your own shipping provider.
 * You must still implement certain methods that are required by
 * ShippingProviderInterface.
 * 
 * Tag your completed provider in the service container with 
 * commerce.shipping_provider to register it.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ShippingProvider implements ShippingProviderInterface
{
    
    /** @var Collection $methods  */
    protected $methods;

    /**
     * @{inheritDoc} 
     */
    public function __construct(ConfigurationManager $configurationManager, CartManager $cartManager)
    {
        $this->configurationManager = $configurationManager;
        $this->cartManager = $cartManager;
        $this->methods = new ShippingMethodCollection();
    }
        
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getCartManager
     *
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->cartManager;
    }
    
    /**
     * @{inheritDoc}
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @{inheritDoc}
     */
    public function getMethod($name)
    {
        $value = null;
        foreach($this->methods as $method){
            if($method->getName() == $name){
                return $method;
            }
        }
        
        throw new \Exception(sprintf('Shipping Method %s For Provider %s Does Not Exist. Available methods are %s',
            $name, 
            $this->getName(),
            implode(', ', $this->getAvailableMethodNames())
        ));
    }

    /**
     * @{inheritDoc}
     */
    public function hasMethod($name)
    {
        return $this->methods->has($name);
    }
    

    /**
     * @{inheritDoc}
     */
    public function addMethod(ShippingMethodInterface $method)
    {
         $this->methods->set($method->getName(), $method);
         return $this;
    }
    
    /**
     * getAvailableMethodNames
     * 
     * @return array
     */
     public function getAvailableMethodNames()
     {
         $return = array();
        foreach($this->methods as $method){
            $return[] = $method->getName();
        }
        return $return;
     }
}

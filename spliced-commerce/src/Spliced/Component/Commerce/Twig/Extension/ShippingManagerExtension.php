<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Twig\Extension;

use Spliced\Component\Commerce\Shipping\ShippingManager;

/**
 * ShippingManagerExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
 class ShippingManagerExtension extends \Twig_Extension
{

    public function __construct(ShippingManager $shippingManager)
    {
        $this->shippingManager = $shippingManager;
    }

    /**
     * getShippingManager
     * 
     * @return ShippingManager
     */
    protected function getShippingManager()
    {
        return $this->shippingManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'get_shipping_provider' => new \Twig_Function_Method($this, 'getShippingProvider'),
            'get_shipping_method_by_full_name' => new \Twig_Function_Method($this, 'getShippingMethodByFullName'),
        );
    }


   /**
    * getShippingMethodByFullName
    * 
    * @param string $fullName
    */
    public function getShippingMethodByFullName($fullName)
    {
        return $this->getShippingManager()->getMethodByFullName($fullName);
    }
    
   /**
    * getShippingProvider
    * 
    * @param string $name
    */
    public function getShippingProvider($name)
    {
        return $this->getShippingManager()->getProvider($name);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_shipping_manager';
    }

}

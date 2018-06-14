<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Controller
 * 
 * Provides additional helper/shortcut methods to a controller 
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class Controller extends BaseController
{
    
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->get('commerce.configuration');
    }
    
    /**
     * getCartManager
     *
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->get('commerce.cart');
    }
    
    /**
     * getCheckoutManager
     *
     * @return CheckoutManager
     */
    public function getCheckoutManager()
    {
        return $this->get('commerce.checkout_manager');
    }
    
    /**
     * getShippingManager
     * 
     * @return ShippingManager
     */
    public function getShippingManager()
    {
        return $this->get('commerce.shipping_manager');
    }
    

    /**
     * getPaymentManager
     *
     * @return PaymentManager
     */
    public function getPaymentManager()
    {
        return $this->get('commerce.payment_manager');
    }
}
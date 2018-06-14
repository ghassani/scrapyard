<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\CustomerInterface;

/**
 * CheckoutNextStepEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutNextStepEvent extends CheckoutEvent
{
    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order, CustomerInterface $newUser = null)
    {
        $this->customer = $newUser;
        parent::__construct($order);
    }
    
    /**
     * getCustomer()
     */
     public function getCustomer()
     {
         return $this->customer;
     }
     
    /**
     * hasRegistration
     */
    public function hasNewCustomerRegistration()
    {
        return $this->customer instanceof CustomerInterface;
    }
}

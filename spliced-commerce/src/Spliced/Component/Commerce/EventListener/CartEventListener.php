<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace  Spliced\Component\Commerce\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Order\OrderManager;

/**
 * CartEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CartEventListener
{
    /**
     * Constructor
     * 
     * @param CartManager $cartManager
     * @param OrderManager $orderManager
     */
    public function __construct(CartManager $cartManager, OrderManager $orderManager)
    {
        $this->cartManager = $cartManager;
        $this->orderManager = $orderManager;
    }

    /**
     * getCartManager
     */
    protected function getCartManager()
    {
        return $this->cartManager;
    }

    /**
     * getOrderManager
     */
    protected function getOrderManager()
    {
        return $this->orderManager;
    }
    /**
     * onCartItemAdd
     */
    public function onCartItemAdd(Events\AddToCartEvent $event)
    {
        if($this->getOrderManager()->hasCurrentOrder()){
            $order = $this->getOrderManager()->getOrder();
            if($order instanceof OrderInterface){
                $this->getOrderManager()->updateOrderItems($order, true);
            }
        }
    }

    /**
     * onCartItemRemove
     */
    public function onCartItemRemove(Events\RemoveFromCartEvent $event)
    {
        if($this->getOrderManager()->hasCurrentOrder()){
            $order = $this->getOrderManager()->getOrder();
            if($order instanceof OrderInterface){
                $this->getOrderManager()->updateOrderItems($order, true);
            }
        }
    }

    /**
     * onCartUpdate
     */
    public function onCartUpdate(Events\CartUpdateEvent $event)
    {
        if($this->getOrderManager()->hasCurrentOrder()){
            $order = $this->getOrderManager()->getOrder();
            if($order instanceof OrderInterface){
                $this->getOrderManager()->updateOrderItems($order, true);
            }
        }  
    }
}

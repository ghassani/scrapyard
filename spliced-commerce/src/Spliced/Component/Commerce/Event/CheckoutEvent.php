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

/**
 * CheckoutEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutEvent extends Event
{

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * getOrder
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * setOrder
     * @param  OrderInterface $order
     * @return CheckoutEvent
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }

}

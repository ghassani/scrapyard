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
use Spliced\Component\Commerce\Model\OrderShipmentMemoInterface;

/**
 * OrderShipmentUpdateEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderShipmentUpdateEvent extends OrderUpdateEvent
{

    /**
     * @param OrderInterface $order
     * @param OrderShipmentMemoInterface $memo
     */
    public function __construct(OrderInterface $order, OrderShipmentMemoInterface $memo)
    {
        $this->order = $order;
        $this->memo = $memo;
    }

    /**
     * getMemo
     *
     * @return OrderShipmentMemoInterface
     */
    public function getMemo()
    {
        return $this->memo;
    }
}

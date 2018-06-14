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

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;

/**
 * OrderExtension
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
 class OrderExtension extends \Twig_Extension
{

    /**
     * Constructor
     * 
     * @param OrderHelper $orderHelper
     */
    public function __construct(OrderHelper $orderHelper)
    {
        $this->orderHelper = $orderHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(            
            'commerce_order_total' => new \Twig_Function_Method($this, 'orderTotal'),
            'commerce_order_subtotal' => new \Twig_Function_Method($this, 'orderSubTotal'),
            'commerce_order_tax' => new \Twig_Function_Method($this, 'orderTax'),
            'commerce_order_shipping' => new \Twig_Function_Method($this, 'orderShipping'),
            'commerce_order_status' => new \Twig_Function_Method($this, 'orderStatus'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'commerce_order';
    }

    /**
     * orderTotal
     *
     * @param OrderInterface $order
     */
    public function orderTotal(OrderInterface $order, $includeShipping = true)
    {
        return $this->orderHelper->getOrderTotal($order, $includeShipping);
    }

    /**
     * orderStatus
     *
     * @param OrderInterface $order
     */
    public function orderStatus(OrderInterface $order)
    {
        return $this->orderHelper->getOrderStatus($order);
    }

    /**
     * orderShipping
     *
     * @param OrderInterface $order
     */
    public function orderShipping(OrderInterface $order)
    {
        return $this->orderHelper->getOrderShipping($order);
    }

    /**
     * orderTax
     *
     * @param OrderInterface $order
     */
    public function orderTax(OrderInterface $order)
    {
        return $this->orderHelper->getOrderTax($order);
    }

    /**
     * orderSubTotal
     *
     * @param OrderInterface $order
     */
    public function orderSubTotal(OrderInterface $order, $includeShipping = true)
    {
        return $this->orderHelper->getOrderSubTotal($order, $includeShipping);
    }
}

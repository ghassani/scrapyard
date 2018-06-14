<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

/**
 * OrderItemInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderItemInterface
{
    /**
     * Get sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get basePrice
     *
     * @return float
     */
    public function getBasePrice();
    /**
     * Get salePrice
     *
     * @return float
     */
    public function getSalePrice();
    /**
     * Get taxes
     *
     * @return float
     */
    public function getTaxes();

    /**
     * Get finalPrice
     *
     * @return float
     */
    public function getFinalPrice();

    /**
     * getOrder
     */
    public function getOrder();

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity();

    /**
     * Get cost
     *
     * @return integer
     */
    public function getCost();
}

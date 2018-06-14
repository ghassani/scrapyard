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

use Spliced\Component\Commerce\Model\ProductInterface;

/**
 * AddToCartEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AddToCartEvent extends Event
{
    /** ProductInterface */
    protected $product;

    /**
     * @param ProductInerface $product
     * @param int             $quantity
     */
    public function __construct(ProductInterface $product, $quantity)
    {
        $this->product     = $product;
        $this->quantity = $quantity;
    }

    /**
     * getProduct
     *
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * getQuantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}

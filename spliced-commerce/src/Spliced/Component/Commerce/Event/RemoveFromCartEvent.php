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
 * RemoveFromCartEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RemoveFromCartEvent extends Event
{
    /** ProductInterface */
    protected $product;

    /**
     * @param ProductInterface $product
     *
     */
    public function __construct(ProductInterface $product)
    {
        $this->product     = $product;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

}

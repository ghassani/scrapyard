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
 * ProductSaveEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSaveEvent extends Event
{
    /** ProductInterface */
    protected $product;

    /**
     * @param ProductInerface $product
     */
    public function __construct(ProductInterface $product)
    {
        $this->product     = $product;
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

}

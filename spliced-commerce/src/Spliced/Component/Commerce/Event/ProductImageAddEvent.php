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
use Spliced\Component\Commerce\Model\ProductImage;

/**
 * ProductSaveEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductImageAddEvent extends Event
{
    /** ProductInterface */
    protected $product;
    
    /** ProductImage */
    protected $productImage;

    /**
     * @param ProductInerface $product
     */
    public function __construct(ProductInterface $product, ProductImage $productImage)
    {
        $this->product     = $product;
        $this->productImage = $productImage;
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
     * getProduct
     *
     * @return ProductImage
     */
    public function getProductImage()
    {
        return $this->productImage;
    }
}

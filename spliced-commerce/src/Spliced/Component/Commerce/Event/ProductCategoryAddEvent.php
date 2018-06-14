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
use Spliced\Component\Commerce\Model\CategoryInterface;

/**
 * ProductSaveEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductCategoryAddEvent extends Event
{
    /** ProductInterface */
    protected $product;
    
    /** CategoryInterface */
    protected $productCategory;

    /**
     * @param ProductInerface $product
     */
    public function __construct(ProductInterface $product, CategoryInterface $productCategory)
    {
        $this->product     = $product;
        $this->productCategory     = $productCategory;
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
     * @return CategoryInterface
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }
}

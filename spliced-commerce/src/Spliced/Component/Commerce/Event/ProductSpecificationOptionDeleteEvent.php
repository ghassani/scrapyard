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

use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;

/**
 * ProductSpecificationOptionDeleteEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSpecificationOptionDeleteEvent extends Event
{
    /** ProductInterface */
    protected $specificationOption;

    /**
     * @param ProductInerface $product
     */
    public function __construct(ProductSpecificationOptionInterface $specificationOption)
    {
        $this->specificationOption     = $specificationOption;
    }
    
    /**
     * getProductSpecificationOption
     *
     * @return ProductSpecificationOptionInterface
     */
    public function getProductSpecificationOption()
    {
        return $this->specificationOption;
    }
    
    /**
     * getSpecificationOption
     *
     * @return ProductSpecificationOptionInterface
     */
    public function getSpecificationOption()
    {
        return $this->specificationOption;
    }

}

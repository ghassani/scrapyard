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

use Spliced\Component\Commerce\Model\ProductSpecificationOptionValueInterface;

/**
 * ProductSpecificationOptionValueDeleteEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductSpecificationOptionValueDeleteEvent extends Event
{
    /** ProductSpecificationOptionValueInterface */
    protected $specificationOptionValue;

    /**
     * @param ProductSpecificationOptionValueInterface $product
     */
    public function __construct(ProductSpecificationOptionValueInterface $specificationOptionValue)
    {
        $this->specificationOptionValue    = $specificationOptionValue;
    }
    
    /**
     * getProductSpecificationOptionValue
     *
     * @return ProductSpecificationOptionValueInterface
     */
    public function getProductSpecificationOptionValue()
    {
        return $this->specificationOptionValue;
    }
    
    /**
     * getSpecificationOptionValue
     *
     * @return ProductSpecificationOptionValueInterface
     */
    public function getSpecificationOptionValue()
    {
        return $this->specificationOptionValue;
    }

}

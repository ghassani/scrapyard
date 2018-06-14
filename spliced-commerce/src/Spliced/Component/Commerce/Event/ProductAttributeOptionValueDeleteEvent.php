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

use Spliced\Component\Commerce\Model\ProductAttributeOptionValueInterface;

/**
 * ProductAttributeOptionValueDeleteEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionValueDeleteEvent extends Event
{
    /** ProductAttributeOptionValueInterface */
    protected $attributeOptionValue;

    /**
     * @param ProductAttributeOptionValueInterface $product
     */
    public function __construct(ProductAttributeOptionValueInterface $attributeOptionValue)
    {
        $this->attributeOptionValue     = $attributeOptionValue;
    }
    
    /**
     * getProductAttributeOptionValue
     *
     * @return ProductAttributeOptionValueInterface
     */
    public function getProductAttributeOptionValue()
    {
        return $this->attributeOptionValue;
    }
    
    /**
     * getAttributeOptionValue
     *
     * @return ProductAttributeOptionValueInterface
     */
    public function getAttributeOptionValue()
    {
        return $this->attributeOptionValue;
    }

}

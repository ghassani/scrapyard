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

use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;

/**
 * ProductAttributeOptionSaveEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionSaveEvent extends Event
{
    /** ProductInterface */
    protected $attributeOption;

    /**
     * @param ProductInerface $product
     */
    public function __construct(ProductAttributeOptionInterface $attributeOption)
    {
        $this->attributeOption     = $attributeOption;
    }
    
    /**
     * getProductAttributeOption
     *
     * @return ProductAttributeOptionInterface
     */
    public function getProductAttributeOption()
    {
        return $this->attributeOption;
    }
    
    /**
     * getAttributeOption
     *
     * @return ProductAttributeOptionInterface
     */
    public function getAttributeOption()
    {
        return $this->attributeOption;
    }

}

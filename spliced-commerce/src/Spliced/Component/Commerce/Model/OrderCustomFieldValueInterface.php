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
 * OrderCustomFieldValueInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderCustomFieldValueInterface
{
    
    /**
     * setOrder
     * 
     *  @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order);
    
    /**
     * getOrder
     * 
     * @return OrderInterface
     */
    public function getOrder();
    
    /**
     * setField
     * 
     * @param CheckoutCustomFieldInterface $field
     */
    public function setField(CheckoutCustomFieldInterface $field);
    
    /**
     * getField
     * 
     * @return string
     */
    public function getField();
    
    /**
     * setFieldValue
     * 
     * @param mixed $fieldValue;
     */
    public function setFieldValue($fieldValue);
    
    /**
     * getFieldValue
     * 
     * @return mixed
     */
    public function getFieldValue();
}

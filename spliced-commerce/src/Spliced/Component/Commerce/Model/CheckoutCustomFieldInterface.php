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
 * CheckoutCustomFieldInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CheckoutCustomFieldInterface
{    
    /**
     * setFieldName
     * 
     * @param string $fieldName
     */
    public function setFieldName($fieldName);
    
    /**
     * getFieldName
     * 
     * @return string
     */
    public function getFieldName();
    
    /**
     * setFieldType
     * 
     * @param string $fieldType
     */
    public function setFieldType($fieldType);
    
    /**
     * getFieldType
     * 
     * @return string
     */
    public function getFieldType();
    
    /**
     * setDescription
     * 
     * @param string $description
     */
    public function setDescription($description);
    
    /**
     * getDescription
     * 
     * @return string
     */
    public function getDescription();
    
    /**
     * setCheckoutHtml
     * 
     * @param string $checkoutHtml
     */
    public function setCheckoutHtml($checkoutHtml);
    
    /**
     * getCheckoutHtml
     * 
     * @return string
     */
    public function getCheckoutHtml();
    
    /**
     * setFieldParams
     * 
     * @param array $fieldParams
     */
    public function setFieldParams(array $fieldParams);
    
    /**
     * getFieldParams
     * 
     * @return array
     */
    public function getFieldParams();
    
    /**
     * setFieldSetp
     * 
     * @param int $fieldStep
     */
    public function setFieldStep($fieldStep);
    
    /**
     * getFieldStep
     * 
     * @return int
     */
    public function getFieldStep();
    
    /**
     * setFieldLabel
     * 
     * @param string $fieldLabel
     */
    public function setFieldLabel($fieldLabel);
    
    /**
     * getFieldLabel
     * 
     * @return string
     */
    public function getFieldLabel();
    
    /**
     * setIsActive
     * 
     * @param bool $isActive
     */
    public function setIsActive($isActive);
    
    /**
     * getIsActive
     * 
     * @return bool
     */
    public function getIsActive();

    /**
     * getValidationParams
     * 
     * @return array
     */
    public function getValidationParams();
    
    /**
     * setValidationParams
     * 
     * @param array $validationParams
     */
    public function setValidationParams(array $validationParams);
}
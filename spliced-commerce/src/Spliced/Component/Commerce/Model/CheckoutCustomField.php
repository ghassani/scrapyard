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

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * CheckoutCustomField
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="checkout_custom_field")
 */
abstract class CheckoutCustomField implements CheckoutCustomFieldInterface
{

    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
     */
    protected $fieldName;

    /**
     * @MongoDB\String
     */
    protected $fieldType;


    /**
     * @MongoDB\String
     */
    protected $fieldLabel;
    

    /**
     * @MongoDB\String
     */
    protected $description;
    

    /**
     * @MongoDB\String
     */
    protected $checkoutHtml;


    /**
     * @MongoDB\Hash
     */
    protected $fieldParams;
    

    /**
     * @MongoDB\Int
     */
    protected $fieldStep;


    /**
     * @MongoDB\Hash
     */
    protected $validationParams;
    

    /**
     * @MongoDB\Boolean
     */
    protected $isActive;
    
    /**
     * getId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setCheckoutHtml($checkoutHtml)
    {
        $this->checkoutHtml = $checkoutHtml;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCheckoutHtml()
    {
        return $this->checkoutHtml;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFieldParams(array $fieldParams)
    {
        $this->fieldParams = $fieldParams;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFieldParams()
    {
        return $this->fieldParams;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFieldStep($fieldStep)
    {
        $this->fieldStep = $fieldStep;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFieldStep()
    {
        return $this->fieldStep;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * {@inheritDoc}
     */
    public function getValidationParams()
    {
        return $this->validationParams;    
    }

    /**
     * {@inheritDoc}
     */
    public function setValidationParams(array $validationParams)
    {
        $this->validationParams = $validationParams;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setFieldLabel($fieldLabel)
    {
        $this->fieldLabel = $fieldLabel;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldLabel()
    {
        return $this->fieldLabel;
    }
}
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
 * ProductAttributeOptionValueProduct
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionValueProduct 
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Product")
     */
    protected $product;

    /**
     * @MongoDB\Float
     */
    protected $priceAdjustment;
    
    /**
     * @MongoDB\Int
     */
    protected $priceAdjustmentType;
        
    /**
     * @MongoDB\Int
     */
    protected $quantity;
    
    /**
     * @MongoDB\Boolean
     */
    protected $allowTierPricing;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quantity = 1;
        $this->allowTierPricing = false;
    }

    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
        return $this;
    }
        
    /**
     * getPriceAdjustment
     */
    public function getPriceAdjustment()
    {
        return $this->priceAdjustment;
    }
     
    /**
     * setPriceAdjustment
     */
    public function setPriceAdjustment($priceAdjustment)
    {
        $this->priceAdjustment = $priceAdjustment;
        return $this;
    }
    
    /**
     * getPriceAdjustmentType
     */
    public function getPriceAdjustmentType()
    {
        return $this->priceAdjustmentType;
    }
     
    /**
     * setPriceAdjustmentType
     */
    public function setPriceAdjustmentType($priceAdjustmentType)
    {
        $this->priceAdjustmentType = $priceAdjustmentType;
        return $this;
    }
    
        
    /**
    * getQuantity
    * 
    * @return int
    */
    public function getQuantity() 
    {
      return $this->quantity;
    }
    
    /**
    * setQuantity
    * 
    * @param int $quantity
    */
    public function setQuantity($quantity) 
    {
      $this->quantity = $quantity;
      return $this;
    }
    
    /**
     * getAllowTierPricing
     *
     * @return bool
     */
    public function getAllowTierPricing()
    {
        return $this->allowTierPricing;
    } 
    
    /**
     * setAllowTierPricing
     *
     * @param bool $allowTierPricing
     */
    public function setAllowTierPricing($allowTierPricing)
    {
        $this->allowTierPricing = $allowTierPricing;
        return $this;
    }
}
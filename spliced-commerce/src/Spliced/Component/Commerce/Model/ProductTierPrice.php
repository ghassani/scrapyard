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

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductTierPrice
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="product_tier_price")
 * @ORM\Entity()
 */
class ProductTierPrice
{
    
     /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="min_quantity", type="integer", nullable=false)
     */
    protected $minQuantity;
    
    /**
     * @ORM\Column(name="max_quantity", type="integer", nullable=false)
     */
    protected $maxQuantity;
    
    /**
     * @ORM\Column(name="adjustment_type", type="smallint", nullable=false)
     */
    protected $adjustmentType;
    
    /** 
     * @ORM\Column(name="adjustment", type="decimal", scale=12, precision=4, nullable=false)
     */
    protected $adjustment;
    
    /** 
     * @ORM\Column(name="options", type="array", nullable=true)
     */
    protected $options;
    
    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="tierPrices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;
    
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getMinQuantity
     *
     */
    public function getMinQuantity()
    {
        return $this->minQuantity;
    }
    
    /**
     * setMinQuantity
     *
     * @param int $value
     */
    public function setMinQuantity($value)
    {
        $this->minQuantity = $value;
    
        return $this;
    }
    
    /**
     * getMaxQuantity
     *
     */
    public function getMaxQuantity()
    {
        return $this->maxQuantity;
    }
    
    /**
     * setMaxQuantity
     *
     * @param int $value
     */
    public function setMaxQuantity($value)
    {
        $this->maxQuantity = $value;
    
        return $this;
    }
    
    /**
     * getAdjustmentType
     *
     */
    public function getAdjustmentType()
    {
        return $this->adjustmentType;
    }
    
    /**
     * setAdjustmentType
     *
     * @param string $value
     */
    public function setAdjustmentType($value)
    {
        $this->adjustmentType = $value;
    
        return $this;
    }
    
    /**
     * getAdjustment
     *
     */
    public function getAdjustment()
    {
        return $this->adjustment;
    }
    
    /**
     * setAdjustment
     *
     * @param float $value
     */
    public function setAdjustment($value)
    {
        $this->adjustment = $value;
    
        return $this;
    }
    
    /**
     * setOptions
     *
     * @param array|null $options
     */
    public function setOptions(array $options = null)
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * getOptions
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
     * setProduct
     *
     * @param ProductInterface product
     *
     * @return self
     */
    public function setProduct(ProductInterface $product)
    {
    	$this->product = $product;
    	return $this;
    }
}
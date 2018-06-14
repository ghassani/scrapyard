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
 * ProductBundledItem
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductBundledItem 
{
    
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="bundledProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;
    
    /**
     * @var Product
     *
     * @ORM\OneToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(name="bundled_product_id", referencedColumnName="id")
     */
    protected $relatedProduct;

    /**
     * @ORM\Column(name="price_adjustment", type="decimal", scale=12, precision=4, nullable=true)
     */
    protected $priceAdjustment;
    
    /**
     * @ORM\Column(name="price_adjustment_type", type="smallint", nullable=true)
     */
    protected $priceAdjustmentType;
        
    /**
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    protected $quantity;
    
    /**
     * @ORM\Column(name="allow_tier_pricing", type="boolean", nullable=true)
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
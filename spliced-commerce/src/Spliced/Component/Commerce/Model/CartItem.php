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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="cart_item")
 * @ORM\Entity()
 */
abstract class CartItem implements CartItemInterface
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
     * @var int $quantity
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    protected $quantity;

    /**
     * @var boolean $nonRemovable
     *
     * @ORM\Column(name="non_removable", type="boolean")
     */
    protected $nonRemovable;

    /**
     * @var boolean $isBundled
     *
     * @ORM\Column(name="is_bundled", type="boolean")
     */
    protected $isBundled;

    /**
     * @var boolean $allowTierPricing
     *
     * @ORM\Column(name="allow_tier_pricing", type="boolean")
     */
    protected $allowTierPricing;
    
    /**
     * @var float $priceAdjustment
     *
     * @ORM\Column(name="price_adjustment", type="decimal")
     */
    protected $priceAdjustment;
    
    /**
     * @var float $priceAdjustmentType
     *
     * @ORM\Column(name="price_adjustment_type", type="decimal")
     */
    protected $priceAdjustmentType;
    
    /**
     * @var array $quantity
     *
     * @ORM\Column(name="item_data", type="array")
     */
    protected $itemData;
    
    /**
     * @var CartInterface $cart
     * 
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="items")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     * })
     */ 
    protected $cart; 
    
    /**
     * @var CartItemInterface $parent
     *
     * @ORM\ManyToOne(targetEntity="CartItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;
    
    /** 
     * @var array $children
     *
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="parent", cascade={"persist"})
     */
    protected $children;
    
    /**
     * @ORM\OneToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;
    
	/**
     * Constructor
	*/
	public function __construct()
	{
		$this->children = new ArrayCollection();
        $this->quantity = 0;
	}

	/**
	 * getId
	*/
	public function getId()
	{
    	return $this->id;
	}
     
    /**
     * getCart
     */
	public function getCart()
	{
         return $this->cart;
     }
     
     /**
      * setCart
      * 
      * @param CartInterface $cart
      */
      public function setCart(CartInterface $cart)
      {
          $this->cart = $cart;
        return $this;
      }
      
      /**
       * getParent
       * 
       * @return CartItemInterface|null
       */
      public function getParent()
      {
          return $this->parent;
      }
      
      /**
       * setParent
       * 
       * @param CartItemInterface $parent
       */
      public function setParent(CartItemInterface $parent)
      {
          $this->parent = $parent;
        return $this;
      }
      
      /**
       * isParent
       * 
       * @return bool
       */
       public function isParent()
       {
           if(!$this->getParent()){
               return true;
           }
           
           return false;
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
        * @param ProductInterface $product|null
        */
       public function setProduct(ProductInterface $product = null)
       {
            $this->product = $product;
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
         * isChild
         *  
         * @return bool
         */
         public function isChild()
         {
             return $this->getParent() ? true : false;
         }
        
        /**
         * hasChildren
         * 
         * @return bool
         */
         public function hasChildren()
         {
             return $this->getChildren()->count() ? true : false;
         }
        
        /**
         * getChildren
         * 
         * @return Collection|array
         */
        public function getChildren()
        {
            return $this->children;
        }
        
        /**
         * setChildren
         * 
         * @param Collection|array $children
         */
        public function setChildren($children)
        {
            $this->children = $children;
            return $this;
        }
        
        /**
         * addChild
         * 
         * @param CartItemInterface $child
         */
         public function addChild(CartItemInterface $child){
             $this->children->add($child);
             return $this;
         }
         
         /**
          * hasChildProduct
          * 
          * @param ProductInterface $product
          * 
          * @return bool
          */
         public function hasChildProduct(ProductInterface $product)
         {
             foreach($this->children as $child){
                 if($child->getProduct()->getId() == $product->getId()){
                     return true;
                 }
             }
             return false;
         }
         
         /**
          * getChildByProduct
          * 
          * @param ProductInterface $product
          * 
          * @return CartItemInterface
          */
         public function getChildByProduct(ProductInterface $product)
         {
             foreach($this->children as $child){
                 if($child->getProduct()->getId() == $product->getId()){
                     return $child;
                 }
             }
             return null;
         }
         
         /**
          * isNonRemovable
          */
         public function isNonRemovable()
         {
            return $this->getNonRemovable();
         }
         
         /**
          * getNonRemovable
          */
         public function getNonRemovable()
         {
            return $this->nonRemovable;
         }
         
         /**
          * setNonRemovable
          */
         public function setNonRemovable($nonRemovable)
         {
            $this->nonRemovable = $nonRemovable;
            return $this;
         }
         

         /**
          * isIsBundled
          */
         public function isIsBundled()
         {
             return $this->getIsBundled();
         }
          
         /**
          * getIsBundled
          */
         public function getIsBundled()
         {
             return $this->isBundled;
         }
          
         /**
          * setIsBundled
          */
         public function setIsBundled($isBundled)
         {
             $this->isBundled = $isBundled;
             return $this;
         }

         /**
          * isAllowTierPricing
          */
         public function allowTierPricing()
         {
             return $this->allowTierPricing;
         }
         
         
         /**
          * getAllowTierPricing
          */
         public function getAllowTierPricing()
         {
             return $this->allowTierPricing;
         }
         
         /**
          * setAllowTierPricing
          */
         public function setAllowTierPricing($allowTierPricing)
         {
             $this->allowTierPricing = $allowTierPricing;
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
          * getItemData
          */
         public function getItemData()
         {
             return $this->itemData;
         }
         
         /**
          * setItemData
          */
          public function setItemData(array $data = array())
          {
              $this->itemData = $data;
              return $this;
          }
}
    
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
 * OrderItem
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="customer_order_item")
 * @ORM\Entity()
 */
abstract class OrderItem implements OrderItemInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="product_id", type="string")
     */
    protected $productId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=100)
     */
    protected $sku;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;
    
    /**
     * @var float
     *
     * @ORM\Column(name="base_price", type="decimal")
     */
    protected $basePrice;
    
    /**
     * @var float
     *
     * @ORM\Column(name="sale_price", type="decimal")
     */
    protected $salePrice;
    
    /**
     * @var float
     *
     * @ORM\Column(name="taxes", type="decimal")
     */
    protected $taxes;
    
    /**
     * @var float
     *
     * @ORM\Column(name="cost", type="decimal")
     */
    protected $cost;
    
    /**
     * @var float
     *
     * @ORM\Column(name="total_price", type="decimal")
     */
    protected $totalPrice;
    
    /**
     * @var float
     *
     * @ORM\Column(name="final_price", type="decimal")
     */
    protected $finalPrice;
    
    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    protected $quantity;
    
    /**
     * @var array $quantity
     *
     * @ORM\Column(name="item_data", type="array")
     */
    protected $itemData;
    
    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;
    
    /**
     * @var OrderItemInterface $parent
     *
     * @ORM\ManyToOne(targetEntity="OrderItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;
    
    /**
     * @var CartItemInterface $cartItem
     *
     * @ORM\OneToOne(targetEntity="CartItem", cascade={"persist"})
     * @ORM\JoinColumn(name="cart_item_id", referencedColumnName="id")
     */
    protected $cartItem;
    
    /**
     * @var array $children
     *
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="parent", cascade={"persist"})
     */
    protected $children;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
     
    /**
     * Set sku
     *
     * @param  string    $sku
     * @return OrderItem
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set name
     *
     * @param  string    $name
     * @return OrderItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set basePrice
     *
     * @param  float     $basePrice
     * @return OrderItem
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * Get basePrice
     *
     * @return float
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * Set salePrice
     *
     * @param  float     $salePrice
     * @return OrderItem
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * Get salePrice
     *
     * @return float
     */
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * Set taxes
     *
     * @param  float     $taxes
     * @return OrderItem
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;

        return $this;
    }

    /**
     * Get taxes
     *
     * @return float
     */
    public function getTaxes()
    {
        return $this->taxes;
    }
    
    /**
     * Set totalPrice
     *
     * @param  float     $totalPrice
     * @return OrderItem
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    
        return $this;
    }
    
    /**
     * Get finalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }
    
    /**
     * Set finalPrice
     *
     * @param  float     $finalPrice
     * @return OrderItem
     */
    public function setFinalPrice($finalPrice)
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }

    /**
     * Get finalPrice
     *
     * @return float
     */
    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

    /**
     * getOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *setOrder
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Set quantity
     *
     * @param  integer   $quantity
     * @return OrderItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set cost
     *
     * @param  decimal   $cost
     * @return OrderItem
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
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
       * @param OrderItemInterface $parent
       */
      public function setParent(OrderItemInterface $parent)
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
             return $this->getChildren() && $this->getChildren()->count() ? true : false;
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
         public function addChild(OrderItemInterface $child){
             $this->children->add($child->setParent($this));
             return $this;
         }
         

         /**
          * getCartItem
          * 
          * @return CartItemInterface|null
          */
         public function getCartItem()
         {
             return $this->cartItem;
         }
         
         /**
          * setCartItem
          * 
          * @param CartItemInterface|null $cartItem
          */
         public function setCartItem(CartItemInterface $cartItem = null)
         {
             $this->cartItem = $cartItem;
             return $this;
         }
         
             
         /**
         * getItemData
         * 
         * @return array
         */
         public function getItemData() 
         {
           return $this->itemData;
         }
         
         /**
         * setItemData
         * @param array $itemData
         */
         public function setItemData(array $itemData = array()) 
         {
           $this->itemData = $itemData;
           return $this;
         }
         
         /**
          * getProductId
          * 
          * @return string
          */
         public function getProductId()
         {
             return $this->productId;
         }
         
         /**
          * setProductId
          * 
          * @param string $productId
          */
         public function setProductId($productId)
         {
             $this->productId = $productId;
             return $this;
         }

}

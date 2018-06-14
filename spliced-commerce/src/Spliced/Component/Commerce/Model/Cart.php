<?php
/*
 * This file is part of the SplicedCommerceBundle package. (c) Spliced Media <http://www.splicedmedia.com/> For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Spliced\Component\Commerce\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @ORM\Table(name="cart")
 * @ORM\Entity()
 */
abstract class Cart implements CartInterface {
    
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /** 
     * @var CustomerInterface $customer
     * 
     * @ORM\OneToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;
    
    /** 
     * @var VisitorInterface $visitor
     * 
     * @ORM\OneToOne(targetEntity="Visitor")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     */
    protected $visitor;
    
    /**
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="cart_id")
     */
     protected $items;
     
    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    
    /**
     * Constructor
     */
    public function __construct() {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
    public function getCustomer() {
        return $this->customer;
    }
    
    /**
     * setCustomer
     *
     * @param CustomerInterface|null $customer            
     */
    public function setCustomer(CustomerInterface $customer = null) {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getVisitor() {
        return $this->visitor;
    }
    
    /**
     * setVisitor
     *
     * @param VisitorInterface $visitor            
     */
    public function setVisitor(VisitorInterface $visitor = null) {
        $this->visitor = $visitor;
        return $this;
    }
    
    /**
     * getItems
     *
     * @return Collection
     */
    public function getItems() {
        return $this->items;
    }
    
    /**
     * setItems
     *
     * @param Collection $items            
     */
    public function setItems($items) {
        $this->items = $items;
        return $this; 
    }
    
    /**
     * addItem
     *
     * @param CartItemInterface $item            
     */
    public function addItem(CartItemInterface $item) {
        $item->setCart($this);
        $this->items->add($item );
        return $this;
    }
    
    /**
     * hasProduct
     * 
     * @param ProductInterface $product
     * @param bool $searchChildren
     * 
     * @return bool
     */
    public function hasProduct(ProductInterface $product, $searchChildren = true) {
        
        $searchItems = function($items, $searchChildren) use($product, &$searchItems) {
            foreach($items as $item ) {
                if ($item->getProduct()->getId() == $product->getId()) {
                    return true;
                }
                if(true === $searchChildren && $item->hasChildren()){
                    $childMatch = $searchItems($item->getChildren(), $searchChildren);
                    if(true === $childMatch){
                        return $childMatch;
                    }
                }
            }
            return false;
        };
    
        return $searchItems($this->getItems(), $searchChildren);
    }
    
    /**
     * hasItem
     *
     * @param CartItemInterface $cartItem
     * @param bool $searchChildren
     *
     * @return bool
     */
    public function hasItem(CartItemInterface $cartItem, $searchChildren = true) {
    
        $searchItems = function($items, $searchChildren) use($cartItem, &$searchItems){
            foreach($items as $item ) {
                if ($item->getId() == $cartItem->getId()) {
                    return true;
                }
                if($item->hasChildren() && true === $searchChildren ){
                    $childMatch = $searchItems($item->getChildren(), $searchChildren);
                    if(true === $childMatch){
                        return $childMatch;
                    }
                }
            }
            return false;
        };
    
        return $searchItems($this->getItems(), $searchChildren);
    }
        
    /**
     * hasSku
     * 
     * @param string $sku
     * @param bool $searchChildren
     * 
     * @return bool
     */
    public function hasSku($sku, $searchChildren = true) {
        
        $searchItems = function($items, $searchChildren) use($sku, &$searchItems){
            foreach($items as $item ) {
                if ($item->getProduct()->getSku() == $sku) {
                    return true;
                }
                if($item->hasChildren() && true === $searchChildren ){
                    $childMatch = $searchItems($item->getChildren(), $searchChildren);
                    if(true === $childMatch){
                        return $childMatch;
                    }
                }
            }
            return false;
        };
        
        return $searchItems($this->getItems(), $searchChildren);
    }

    
    /**
     * getItemByProduct
     * 
     * @param ProductInterface $product
     * @param bool $searchChildren
     * 
     * @return CartItemInterface|false
     */
    public function getItemByProduct(ProductInterface $product, $searchChildren = true) {
        $searchItems = function ($items, $searchChildren) use($product, &$searchItems) {
            foreach($items as $item ) {
                if ($item->getProduct()->getId() == $product->getId()) {
                    return $item;
                }
                if (true === $searchChildren && $item->hasChildren()) {
                    $childMatch = $searchItems($item->getChildren(), $searchChildren );
                    if (false !== $childMatch) {
                        return $childMatch;
                    }
                }
            }
            return false;
        };
        
        return $searchItems($this->getItems(), $searchChildren);
    }
    
    /**
     * getItemBySku
     * 
     * @param string $sku
     * @param bool $searchChildren
     * 
     * @return CartItemInterface|false
     */
    public function getItemBySku($sku, $searchChildren = true) {
        $searchItems = function($items, $searchChildren) use($sku, &$searchItems){
            foreach($items as $item ) {
                if ($item->getProduct()->getSku() == $sku) {
                    return $item;
                }
                if($item->hasChildren() && true === $searchChildren ){
                    $childMatch = $searchItems($item->getChildren(), $searchChildren);
                    if(false !== $childMatch){
                        return $childMatch;
                    }
                }
            }
            return false;
        };
        
        return $searchItems($this->getItems(), $searchChildren);
    }
    
    /**
     * getItemById
     * 
     * Looks for an item by ID
     * 
     * @param int $itemId
     * @param bool $searchChildren
     * 
     * @return CartItemInterface|false $item
     */
    public function getItemById($itemId, $searchChildren = true) {
        
        $searchItems = function ($items, $searchChildren) use($itemId, &$searchItems) {
            foreach($items as $item ) {
                if ($item->getId() == $itemId) {
                    return $item;
                }
                if (true === $searchChildren && $item->hasChildren()) {
                    $childMatch = $searchItems($item->getChildren(), $searchChildren );
                    if (false !== $childMatch) {
                        return $childMatch;
                    }
                }
            }
            return false;
        };
                
        return $searchItems($this->getItems(), $searchChildren );
    }
    
    /**
     * getCreatedAt
     *
     * @return DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param DateTime
     */
    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }
} 
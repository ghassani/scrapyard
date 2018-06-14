<?php
/*
 * This file is part of the SplicedCommerceBundle package.
 *
 * (c) Spliced Media <http://www.splicedmedia.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Spliced\Component\Commerce\Cart;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Spliced\Component\Commerce\Model\CartInterface;
use Spliced\Component\Commerce\Model\CartItemInterface;
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Model\ProductOptionInterface;
use Spliced\Component\Commerce\Visitor\VisitorManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;

/**
 * CartManager
 * 
 * Handles all cart managment from adding, removing, and updating
 * items in the shopping cart.
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CartManager {
    
    /** Tag used to save current cart id */
    const SESSION_TAG_CURRENT_CART_ID = 'commerce.cart.id';
    
    /** Shipping quote related tags */
    const SESSION_TAG_SHIPPING_DESTINATION 			= 'commerce.cart.shipping_destination';
    const SESSION_TAG_SHIPPING_DESTINATION_ZIPCODE 	= 'commerce.cart.shipping_destination_zipcode';
    const SESSION_TAG_SHIPPING_PROVIDER 			= 'commerce.cart.shipping.provider';
    const SESSION_TAG_SHIPPING_METHOD 				= 'commerce.cart.shipping.method';

    /* @param CartInterface|null */
    protected $cart;
    /* @param Session */
    protected $session;
    /* @param SecurityContext */
    protected $securityContext;
    /* @param ObjectManager */
    protected $om;

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param Session $session, SecurityContext $securityContext
     * @param VisitorManager $visitorManager
     */
    public function __construct(ConfigurationManager $configurationManager, Session $session, SecurityContext $securityContext, VisitorManager $visitorManager
    ) {
        $this->session = $session;
        $this->configurationManager = $configurationManager;
        $this->securityContext = $securityContext;
        $this->visitorManager = $visitorManager;

    }

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager() {
        return $this->configurationManager;
    }

    /**
     * getVisitorManager
     *
     * @return VisitorManager
     */
    protected function getVisitorManager() {
        return $this->visitorManager;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    protected function getEntityManager() {
        return $this->getConfigurationManager()->getEntityManager();
    }
    
    /**
     * getSession
     *
     * @return Session
     */
    protected function getSession() {
        return $this->session;
    }

    /**
     * getSecurityContext
     *
     * @return SecurityContext
     */
    protected function getSecurityContext() {
        return $this->securityContext;
    }

    /**
     * getCartId
     *
     * @return int|null
     */
    public function getCartId() {
        return $this->getSession()->get(static::SESSION_TAG_CURRENT_CART_ID);
    }

    /**
     * setCartId
     *
     * @param int $cartId
     */
    public function setCartId($cartId) {
        $this->getSession()->set(static::SESSION_TAG_CURRENT_CART_ID, $cartId);
        return $this;
    }

    /**
     * getCart
     * 
     * @param bool $createIfNotFound - Will create one if none found
     * 
     * @return CartInterface|false
     */
    public function getCart($createIfNotFound = false) {
        if (isset($this->cart) && $this->cart instanceof CartInterface) {
            return $this->cart;
        }
        
        // first try to find we have already saved the cart id to the session
        if ($this->getCartId()) {
            $this->cart = $this->getEntityManager()
              ->getRepository($this->getConfigurationManager()
              ->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CART))
              ->findOneByIdWithItems($this->getCartId());
        }
        
        if(!$this->cart instanceof CartInterface){
            if(true === $createIfNotFound){
                $this->createCart(false);
            } else {
                $this->setCartId(null);
                return false;
            }
        }
        
        if($this->cart instanceof CartInterface) {
        	// organize the items
        	if(count($this->cart->getItems())){
	        	$newItems = new \Doctrine\Common\Collections\ArrayCollection();
	        	foreach($this->cart->getItems() as $item){
	        		if(!$item->getParent()) {
	        			$newItems->add($item);
	        		}
	        	}
	        	
	        	$this->cart->setItems($newItems);
        	}
        	
        	 
        	
            /*$visitor = $this->getVisitorManager()->getCurrentVisitor();
            
            // check to make sure that this cart belongs to this visitor or customer
            if($visitor && $this->cart->getVisitor()->getId() != $visitor->getId()){
             
            } elseif($this->cart->getCustomer()
                    && $this->getSecurityContext()->isGranted('ROLE_USER')
                    && $this->cart->getCustomer()->getId() !== $this->getSecurityContext()->getToken()->getUser()->getId()){
             
            }
            
            $productIds = $this->getProductIds();
			
            if(!isset($this->products) && count($productIds)){
                // load products associated with the cart items and
                $this->products = $this->getEntityManager()
                  ->getRepository($this->getConfigurationManager()->getEntityClass(
                      ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT
                  ))->createQueryBuilder('product')
                  ->select('product')
                  ->where('product.id IN (:ids)')
                  ->setParameter('ids', $productIds)
                  ->getQuery()
                  ->getResult();
            
                $setProducts = function(Collection $items, $products) use(&$setProducts) {
                    foreach($items as $item){
                        foreach($products as $product){
                            if($product->getId() == $item->getProduct()->getId()) {
                                $item->setProduct($product);
                            }
                        }
                        if($item->hasChildren()) {
                            $setProducts($item->getChildren(), $products);
                        }
                    }
                };
                
                $setProducts($this->cart->getItems(), $this->products);
            }
            */
        	        
            if($this->getSecurityContext()->isGranted('ROLE_USER')) {
                $this->cart->setCustomer($this->getSecurityContext()->getToken()->getUser());
            }
            
            $this->getEntityManager()->persist($this->cart);
            $this->getEntityManager()->flush();
    
            $this->setCartId($this->cart->getId());
    
            return $this->cart; 
        }
        
        $this->setCartId(null);
        
        return false;
    }

    /**
     * createCart
     * 
     * Creates a Cart and sets its ID to the session for persistence
     * 
     * @return CartInterface
     */
    public function createCart($flush = false)
    {
        $this->cart = $this->getConfigurationManager()
          ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CART)
          ->setVisitor($this->getVisitorManager()->getCurrentVisitor());
        
        if(true === $flush){
            $this->getEntityManager()->persist($this->cart);
            $this->getEntityManager()->flush();
            $this->setCartId($this->cart->getId());
        }
        
        return $this->cart;
    }
    
    /**
     * Reset
     * 
     * Resets teh shopping cart, deleteing all items associated with it
     * 
     * @param bool $flush
     * 
     * @return CartManager
     */
    public function reset($flush = true) {
        $cart = $this->getCart();
        
        if(!$cart instanceof CartInterface){
            return $this;
        }
                
        $this->getEntityManager()->remove($cart);
        
        if($flush === true) {
            $this->getEntityManager()->flush();
        }
        
        $this->setCartId(null);
        
        $this->cart = null;
        
        return $this;
    }
    
    /**
     * addFlash
     *
     * Add a flash message to be displayed to the user
     *
     * @param string $type - error, info, warning, or success
     * @param string $message
     */
    public function addFlash($type, $message)
    {
        $type = strtolower($type);
    
        if(!in_array($type, array('error','info','success','warning'))){
            $type = 'info';
        }
    
        $this->getSession()->getFlashBag()->add('cart_'.$type, $message);
        return $this;
    }
    /**
     * add
     *
     * @param ProductInterface $product
     * @param int $quantity
     */
    public function add(ProductInterface $product, $quantity = 1)
    {
        $quantity = (int) $quantity;

        if ($quantity <= 0) { 
            // if we have a 0 or negative number, lets set it to 1
            $quantity = 1;
        }

        if ($product->getMinOrderQuantity() && $product->getMinOrderQuantity() > $quantity) {
            $this->addFlash('info', sprintf('%s requires a minimum of %s. Quantity has been automatically adjusted.',
                $product->getName(), $product->getMinOrderQuantity()
            ));
            
            $quantity = $product->getMinOrderQuantity();
        }

        if ($product->getMaxOrderQuantity() && $product->getMaxOrderQuantity() < $quantity) {
            $this->addFlash('info', sprintf('%s only allows a maximum of %s. Quantity has been automatically adjusted.',
                $product->getName(), $product->getMaxOrderQuantity()
            ));
            $quantity = $product->getMaxOrderQuantity();
        }

        if(in_array($product->getAvailability(), array(
            ProductInterface::AVAILABILITY_BACKORDERED, 
            ProductInterface::AVAILABILITY_CALL_TO_ORDER, 
            ProductInterface::AVAILABILITY_BUILT_TO_ORDER, 
            ProductInterface::AVAILABILITY_UNAVAILABLE
        ))) {
            // remove unavailble products that are attempted to be added to cart
            $this->removeByProduct($product, false);

            // let the user know
            if (in_array($product->getAvailability(), array(
                ProductInterface::AVAILABILITY_CALL_TO_ORDER, 
                ProductInterface::AVAILABILITY_BUILT_TO_ORDER
            ))) {
                $this->getSession()->getFlashBag()->add('warning', sprintf('%s is currently only availble for purchase by contacting us.', $product->getName()));
            } else {
                $this->getSession()->getFlashBag()->add('warning', sprintf('%s is currently unavailble for purchase.', $product->getName()));
            }

            return $this;
        }
        
        // TODO: Manage Variants
        
        // get the current cart instance or create a new one
        $cart = $this->getCart(true); 

        // get the item or create one
        $item = $cart->getItemByProduct($product, false);
        if(!$item instanceof CartItemInterface ){
            $item = $this->getConfigurationManager()
             ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CART_ITEM)
             ->setProduct($product);
                        
            $cart->addItem($item);
        }
        

        if($item->getQuantity() > 0){
        	$quantity += $item->getQuantity();
        }
        
        $item->setQuantity($quantity);
                       
        /* check and handle bundled items
        if($product->getBundledItems()){
            $this->processBundledItems($product, $cartItem);
        }*/

        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();
        
        return $this;
    }
    
    /**
     * update
     *
     * @param ProductInterface $product
     * @param int $quantity
     */
    public function update(CartItemInterface $item, $quantity = null) 
    {        
        $product = $item->getProduct();
        
        if(is_null($quantity)){
            $quantity = $item->getQuantity();
        }
        
        if($quantity === 0){
            $this->remove($item);
            return;
        }

        if ($product->getMinOrderQuantity() && $product->getMinOrderQuantity() > $quantity) {
            $this->addFlash('info', sprintf('%s requires a minimum of %s. Quantity has been automatically adjusted.',
                $product->getName(), $product->getMinOrderQuantity()
            ));
            $quantity = $product->getMinOrderQuantity();
        }

        if ($product->getMaxOrderQuantity() && $product->getMaxOrderQuantity() < $quantity) {
            $this->addFlash('info', sprintf('%s only allows a maximum of %s. Quantity has been automatically adjusted.',
                $product->getName(), $product->getMaxOrderQuantity()
            ));
            $quantity = $product->getMaxOrderQuantity();
        }
        
        /*
        if(!$item->isChild()) {
            // let this method handle child item quantities on it
            // when it is called over each item in the shopping cart
            $item->setQuantity($quantity);
        }*/
        $item->setQuantity($quantity);
        $this->getEntityManager()->persist($item);

        /*
        if($product->getBundledItems()){
	        foreach($product->getBundledItems() as $bundledItem) {
	            // bundled items quantity are set on a per parent item basis
	            // quantity can be increased by setting a bundled items quantity
	            // which will be multiplied by the parent item quantity if set
	            $bundledItemQuantity = $item->getQuantity() * ($bundledItem->getQuantity() ? $bundledItem->getQuantity() : 1);
	            
	            if($item->hasChildProduct($bundledItem->getProduct())){
	                   $bundledCartItem = $item->getChildByProduct($bundledItem->getProduct())
	                     ->setQuantity($bundledItemQuantity);
	
	                $this->getEntityManager()->persist($bundledCartItem);
	            }
	        }
        }*/
         
        $this->getEntityManager()->flush();
    }


    
    /**
     * updateByProduct
     *
     * @param ProductInterface $product
     * @param int $quantity
     */
    public function updateByProduct(ProductInterface $productOrItem, $quantity = 1, array $itemData = array()) 
    {
        if(!$this->has($product)){
            return false;
        }
                
        if($quantity === 0){
            $this->removeByProduct($product);
            return;
        }
        
        
        if ($product->getMinOrderQuantity() && $product->getMinOrderQuantity() > $quantity) {
            $quantity = $product->getMinOrderQuantity();
        }

        if ($product->getMaxOrderQuantity() && $product->getMaxOrderQuantity() < $quantity) {
            $quantity = $product->getMaxOrderQuantity();
        }

        $cartItem = $this->getCart()->getItemByProduct($product);
            
        $cartItem->setQuantity($quantity)->setItemData($itemData);
            
        $this->getEntityManager()->persist($cartItem);
                            
        $this->getEntityManager()->flush();     
        
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
        if($this->getCart()){
            return $this->getCart()->hasItem($cartItem, $searchChildren);
        }
        return false;
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
        if($this->getCart()){
            return $this->getCart()->hasProduct($product, $searchChildren);
        }
        return false;
    }

    /**
     * hasSku
     * @param string $sku
     * 
     * @return bool
     */
    public function hasSku($sku, $searchChildren = true) {
        if($this->getCart()){
            return $this->getCart()->hasSku($sku, $searchChildren);
        }
        return false;
    }

    /**
     * getQuantity
     * 
     * @param ProductInterface $product
     * @param int $default
     * 
     * @return int
     */
    public function getQuantity(ProductInterface $product, $default = 0) {
        if(!$this->getCart()){
            return $default;
        }
        
        foreach ($this->getCart()->getItems() as $item) {
            if ($item->getProduct()->getId() == $product->getId()) {
                return $item->getQuantity();
            }
        }
        
        return $default;
    }

    /**
     * getTotalItems
     * 
     * Get the total number of items in the shopping cart,
     * including children items and quantity of each item
     * taken into account
     * 
     * @return int
     */
    public function getTotalItems() {

        if(!$this->getCart()){
            return 0;
        }
        
        $countItems = function($items) use(&$countItems){
            $return = 0;
            foreach($items as $item){
                $return += $item->getQuantity();
                if($item->hasChildren()){
                    $return += $countItems($item->getChildren());
                }
            }
            return $return;
        };
        
        return $countItems($this->getCart()->getItems());
    }
    
    /**
     * remove
     * 
     * Remove an item from the shopping cart by product
     * 
     * @param ProductInterface $product
     * @param bool $flush - Flushes the ObjectManager after deletion
     * 
     * @return self
     */
    public function remove(CartItemInterface $item, $flush = true) {
        $this->getEntityManager()->remove($item);
        
        if(true === $flush){
            $this->getEntityManager()->flush();
        }

        return $this;
    }
    
    /**
     * remove
     * 
     * Remove all items from the shopping cart by product.
     * Only parent items are removed
     * 
     * @param ProductInterface $product
     * 
     * @param bool $flush - Flushes the ObjectManager after deletion
     */
    public function removeByProduct(ProductInterface $product, $flush = true) {
        
        if(!$this->getCart()){
            return $this;
        }
        
        foreach($this->getCart()->getItems() as $item){
            if($item->getProduct()->getId() == $product->getId() && !$item->isNonRemovable()){
                $this->getEntityManager()->remove($item);
            }
        }
        
        if(true === $flush){
            $this->getEntityManager()->flush();
        }

        return $this;
    }
    
    /**
     * getProductIds
     *
     * Returns an array of product id's in the shopping cart
     *
     * @return array
     */
    public function getProductIds() {
        
        if(!$this->getCart()){
            return array();
        }
        
        $searchItems = function(Collection $items) use(&$searchItems) {
            $return = array();
            foreach($items as $item) {
                $return[] = $item->getProduct()->getId();
                if($item->hasChildren()){
                     $return = array_merge($return, $searchItems($item->getChildren()));
                } 
            }
            return array_unique($return);
        };
    
        return $searchItems($this->getItems());
    }
    
    /**
     * getItems
     *
     * Returns a Collection of CartItemInterface
     * 
     * @return Collection
     */
    public function getItems() {
        if(!$this->getCart()){
            return new ArrayCollection();
        }
        return $this->getCart()->getItems();
    }

    /**
     * setShippingMethod
     * 
     * @param string $method
     * 
     * @return self
     */
    public function setShippingMethod($method = null) {
        $this->session->set(self::SESSION_TAG_SHIPPING_METHOD, $method);

        return $this;
    }

    /**
     * getShippingMethod
     * 
     * @return string
     */
    public function getShippingMethod() {
        return $this->session->get(self::SESSION_TAG_SHIPPING_METHOD, null);
    }

    /**
     * setShippingProvider
     * 
     * @param string $provider
     * 
     * @return self
     */
    public function setShippingProvider($provider = null) {
        $this->session->set(self::SESSION_TAG_SHIPPING_PROVIDER, $provider);

        return $this;
    }

    /**
     * getShippingProvider
     * 
     * @return string
     */
    public function getShippingProvider() {
        return $this->session->get(self::SESSION_TAG_SHIPPING_PROVIDER, null);
    }

    /**
     * setShippingDestination
     * 
     * @param string $shippingDestination
     * 
     * @return self
     */
    public function setShippingDestination($shippingDestination = null) {
        $this->session->set(self::SESSION_TAG_SHIPPING_DESTINATION, $shippingDestination);

        return $this;
    }

    /**
     * getShippingDestination
     * 
     * @return string
     */
    public function getShippingDestination() {
        return $this->session->get(self::SESSION_TAG_SHIPPING_DESTINATION, null);
    }

    /**
     * setShippingDestinationZipcode
     *
     * @param string $shippingDestinationZipcode
     * 
     * @return self
     */
    public function setShippingDestinationZipcode($shippingDestinationZipcode = null) {
        $this->session->set(self::SESSION_TAG_SHIPPING_DESTINATION_ZIPCODE, $shippingDestinationZipcode);

        return $this;
    }

    /**
     * getShippingDestinationZipcode
     */
    public function getShippingDestinationZipcode() {
        return $this->session->get(self::SESSION_TAG_SHIPPING_DESTINATION_ZIPCODE, null);
    }

    /**
     *
     */
    public function hasSetShippingDestination() {
        $shippingDestination = $this->session->get(self::SESSION_TAG_SHIPPING_DESTINATION);
        if (!$shippingDestination) {
            return false;
        }
        if ($shippingDestination == 'US' && !$this->session->has(self::SESSION_TAG_SHIPPING_DESTINATION_ZIPCODE)) {
            return false;
        }

        return true;
    }

    /**
     * getShippingDestinationArray
     * 
     * @return array
     */
    public function getShippingDestinationArray() {
        return array('country' => $this->getShippingDestination(), 'zipcode' => $this->getShippingDestinationZipcode(), );
    }

    /**
     * processBundledItems
     * 
     * @param ProductInterface $product
     * @param CartItemInterface $cartItem
     * 
     * @return bool
     */
    private function processBundledItems(ProductInterface $product, CartItemInterface $cartItem)
    {
        
        if(!count($product->getBundledItems())){
            return false;
        }
 
        // check and handle bundled items
        foreach ($product->getBundledItems() as $bundledItem) {
            // bundled items quantity are set on a per parent item basis
            // quantity can be increased by setting a bundled items quantity
            // which will be multiplied by the parent item quantity if set
            $bundledItemQuantity = (int) ($cartItem->getQuantity() * ($bundledItem->getQuantity() ? $bundledItem->getQuantity() : 1));
        
            if($cartItem->hasChildProduct($bundledItem->getProduct())){
                $bundleCartItem = $cartItem->getChildByProduct($bundledItem->getProduct());
            } else {
                $bundleCartItem = $this->getConfigurationManager()
                ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CART_ITEM)
                ->setProductId($bundledItem->getProduct()->getId())
                ->setProduct($bundledItem->getProduct())
                ->setCart($this->getCart())
                ->setParent($cartItem);
            }
             
            $bundleCartItem->setQuantity($bundledItemQuantity)
              ->setNonRemovable(true)
              ->setIsBundled(true)
              ->setPriceAdjustment($bundledItem->getPriceAdjustment())
              ->setPriceAdjustmentType($bundledItem->getPriceAdjustmentType());
             
            $cartItem->addChild($bundleCartItem);
            
            // call recursively for bundled items of bundled items
            if(count($bundledItem->getProduct()->getBundledItems())){
                $this->processBundledItems($bundledItem->getProduct(), $bundleCartItem);
            }
        }
        return true;
    }
}

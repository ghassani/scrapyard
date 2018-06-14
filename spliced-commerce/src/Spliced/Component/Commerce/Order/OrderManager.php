<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Order;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\ProductAttributeOptionInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Order\OrderHelper;
use Spliced\Component\Commerce\Product\ProductPriceHelper;
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Spliced\Component\Commerce\Logger\Logger;
use Spliced\Component\Commerce\Affiliate\AffiliateManager;
 
/**
 * OrderManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderManager
{
    const SESSION_TAG_CURRENT_ORDER         = 'commerce.order.order_id';
    const SESSION_TAG_LAST_COMPLETED_ORDER  = 'commerce.order.last_completed_order';
    
    protected $order;
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param CartManager          $cartManager
     * @param OrderHelper          $orderHelper
     * @param EncryptionManager    $encryptionManager
     * @param AffiliateManager     $affiliateManager
     * @param Session              $session
     * @param SecurityContext      $securityContext
     * @param Logger               $logger
     */
    public function __construct(
        ConfigurationManager $configurationManager, 
        CartManager $cartManager,
        OrderHelper $orderHelper,
        ProductPriceHelper $priceHelper,
        EncryptionManager $encryptionManager, 
        AffiliateManager $affiliateManager, 
        Session $session,
        SecurityContext $securityContext,
        Logger $logger
    )
    {
        $this->configurationManager = $configurationManager;
        $this->cartManager = $cartManager;
        $this->orderHelper = $orderHelper;
        $this->priceHelper = $priceHelper;
        $this->encryptionManager = $encryptionManager;
        $this->affiliateManager = $affiliateManager;
        $this->session = $session;
        $this->securityContext = $securityContext;
        $this->logger = $logger;
        
        
    }
    
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getSecurityContext
     * 
     * @return SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->securityContext;
    }
 
    /**
     * getLogger
     * 
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }
    
    /**
     * getSession
     * 
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
    }
    
    /**
     * getCartManager
     * 
     * @return CartManager
     */
    protected function getCartManager()
    {
        return $this->cartManager;
    }
    
    /**
     * getOrderHelper
     * 
     * @return OrderHelper
     */
    protected function getOrderHelper()
    {
        return $this->orderHelper;
    }
    
    /**
     * getCheckoutManager
     * 
     * @return CheckoutManager
     */ 
    protected function getCheckoutManager()
    {
        return $this->checkoutManager;
    }
    
    /**
     * getAffiliateManager
     * 
     * @return AffiliateManager
     */ 
    protected function getAffiliateManager()
    {
        return $this->affiliateManager;
    }
    
    /**
     * getEncryptionManager
     * 
     * @return EncryptionManager
     */ 
    protected function getEncryptionManager()
    {
        return $this->encryptionManager;
    }
    
    /**
     * getPriceHelper
     *
     * @return ProductPriceHelper
     */
    protected function getPriceHelper()
    {
        return $this->priceHelper;
    }
     
    /**
     * getEntityManager
     * 
     * @return EntityManager
     */ 
    protected function getEntityManager()
    {
        return $this->getConfigurationManager()->getEntityManager();
    }
    
    /**
     * reset
     * 
     * Resets the current order context 
     * 
     * @param int|null $lastCompletedOrder - Optionally set a last completed order
     * @param bool $deleteOrder - If true, will delete the order completely from the database
     */
    public function reset($lastCompletedOrder = null, $deleteOrder = false)
    {
        
        if($this->getOrder() instanceof OrderInterface && true === $deleteOrder){
            $this->getEntityManager()->remove($this->getOrder());
            $this->getEntityManager()->flush();
        }
        
        $this->setCurrentOrderId(null)
          ->setLastCompletedOrderId($lastCompletedOrder);
          
        return $this;
    }
    
    /**
     * getOrder
     * 
     * Retrieves the current order, if none are available
     * returns null
     */
    public function getOrder()
    {
        if($this->order instanceof OrderInterface){
            return $this->order;
        }
        
        if ($this->hasCurrentOrder()) {
            try {

                $this->order = $this->getEntityManager()->getRepository('SplicedCommerceBundle:Order')
                  ->findOneById($this->getCurrentOrderId());
                                
                return $this->order;
                
            } catch (NoResultException $e) {
                return null;
            }
        }
        
        return null;
    }
    
    /**
     * createOrder
     * 
     * Creates a new order, however if one is already found to be set
     * in the session, it will attempt to use that order instead
     * 
     * @param Request - The Request object, to obtain user information
     */
    public function createOrder(Request $request = null)
    {
        if ($this->hasCurrentOrder()) {
            $order = $this->getOrder();
            if ($order instanceof OrderInterface) {
                return $order;
            }    
        }
        
        $order = $this->getConfigurationManager()
          ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER);
        
        if ($request instanceof Request) {
            $order->setStartIp($request->getClientIp());
        }
        
        if ($this->getSecurityContext()->isGranted('ROLE_USER')) {
            $order->setCustomer($this->getSecurityContext()->getToken()->getUser());
        }

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        $this->setCurrentOrderId($order->getId());
        
        return $order;
    }
    
    /**
     * updateOrder
     * 
     * Updates an order and its relations
     *
     * @param OrderInterface $order
     */
    public function updateOrder(OrderInterface $order)
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
        return $this;
    }
     
    /**
     * deleteOrder
     * 
     * @param OrderInterface $order
     */
    public function deleteOrder(OrderInterface $order)
    {
        $this->getEntityManager()->remove($order);
        $this->getEntityManager()->flush();
        return $this;
    }
    
    /**
     * updateOrderItems
     *
     * Updates an orders items based upon the
     * shopping cart contents. Use this method
     * to check if a user has updated their cart
     * so we can update an order to reflect those changes
     *
     * @param OrderInterface $order
     * @param bool $updateOrder - Save/Update the order to the dababase upon completion
     */
    public function updateOrderItems(OrderInterface $order, $updateOrder = false)
    {
        
        $configurationManager = $this->getConfigurationManager();
        $priceHelper = $this->getPriceHelper();
        
        if(!$this->getCartManager()->getCart()){//no cart to process
            return $this;
        }
        
        $checkCartItems = function($items, $parentItem = null) use($order, $configurationManager, $priceHelper, &$checkCartItems){
            foreach($items as $item){
                
                $orderItem = $order->getItemByCartItem($item);
                
                if (!$orderItem) {
                    $orderItem = $configurationManager
                    ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_ITEM)
                    ->setOrder($order)
                    ->setCartItem($item)
                    ->setSku($item->getProduct()->getSku())
                    ->setName($item->getProduct()->getName())
                    ->setProductId($item->getProduct()->getId());
                }
                
                $itemData = is_array($item->getItemData()) ? $item->getItemData() : array();
                
                // transform selected value id's to value labels for retention
                // in future reports/attribute value changes/deletions
                
                if(isset($itemData['user_data']) && is_array($itemData['user_data'])){
                    foreach($itemData['user_data'] as $attributeName => &$attributeValue){
                        $attribute = $item->getProduct()->getAttribute(preg_replace('/\_\d{1,}$/', '', $attributeName));
                        if($attribute && $attribute->getOption()->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_SELECTION){
                            foreach($attribute->getOption()->getValues() as $v){
                                if($v->getId() == $attributeValue){
                                    $attributeValue = array(
                                        'option_id' => $attribute->getOption()->getId(),
                                        'option_name' => $attribute->getOption()->getName(),
                                        'option_label' => $attribute->getOption()->getPublicLabel(),
                                        'value' => $v->getValue(),
                                        'user_value' => $v->getPublicValue(),
                                        'price_adjustment_type' => $v->getPriceAdjustmentType(),
                                        'price_adjustment' => $v->getPriceAdjustment(),
                                    );
                                }
                            }
                        } else if($attribute && $attribute->getOption()->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT){
                            $attributeValue = array(
                                'option_id' => $attribute->getOption()->getId(),
                                'option_name' => $attribute->getOption()->getName(),
                                'option_label' => $attribute->getOption()->getPublicLabel(),
                                'value' => $attributeValue,
                                'user_value' => $attributeValue,
                            );
                        }
                    }
                }
                
                $orderItem->setQuantity($item->getQuantity())
                  ->setCost($item->getProduct()->getCost())
                  ->setBasePrice($priceHelper->getBasePrice($item->getProduct()))
                  ->setSalePrice($priceHelper->getPrice($item->getProduct(), $item->getQuantity(), null, $item))
                  ->setTaxes($priceHelper->getTax($item->getProduct(), $item))
                  ->setTotalPrice($priceHelper->getPriceTotal($item->getProduct(), $item->getQuantity(), null, $item))
                  ->setFinalPrice($orderItem->getTotalPrice() + $orderItem->getTaxes())
                  ->setItemData($itemData); 
                
                if (!$orderItem->getId()) {
                    $order->addItem($orderItem);
                }
                
                if(!is_null($parentItem)){
                    $orderItem->setParent($parentItem);
                }
                
                if($item->hasChildren()){
                    $checkCartItems($item->getChildren(), $orderItem);
                }
            }
        };
        
        $cart = $this->getCartManager()->getCart();
        
        $checkCartItems($cart->getItems());

        // now we check to see if we need to remove any items
        foreach($order->getItems() as $item){
            if(!$item->getCartItem()){
                // no cart item relation, this should be deleted
                $this->getEntityManager()->remove($item);
                $order->removeItem($item);
                continue;
            }
            
            $cartItem = $cart->getItemById($item->getCartItem()->getId());
            
            if(!$cartItem){
                $this->getEntityManager()->remove($item);
                $order->removeItem($item);
            }
        } 
        
        if(true === $updateOrder){
            $this->updateOrder($order);
        }
    
        return $this;
    }
    
    /**
     * hasCurrentOrder
     * 
     * @return bool
     */
    public function hasCurrentOrder()
    {
        return $this->getSession()->get(static::SESSION_TAG_CURRENT_ORDER, null) ? true : false;
    }
    
    /**
     * getCurrentOrderId
     * 
     * @return int|null
     */
    public function getCurrentOrderId()
    {
        return $this->getSession()->get(static::SESSION_TAG_CURRENT_ORDER, null);
    }
    
    /**
     * setCurrentOrderId
     * 
     * @param int $orderId
     */
    public function setCurrentOrderId($orderId)
    {
        $this->getSession()->set(static::SESSION_TAG_CURRENT_ORDER, $orderId);
        return $this;
    }
    
    
    /**
     * hasLastCompletedOrder
     * 
     * @return bool
     */
    public function hasLastCompletedOrder()
    {
        return $this->getSession()->get(static::SESSION_TAG_LAST_COMPLETED_ORDER, null) ? true : false;
    }
    
    /**
     * getLastCompletedOrderId
     * 
     * @return int|null
     */
    public function getLastCompletedOrderId()
    {
        return $this->getSession()->get(static::SESSION_TAG_LAST_COMPLETED_ORDER, null);
    }
    
    /**
     * setLastCompletedOrderId
     * 
     * @param int $orderId
     */
    public function setLastCompletedOrderId($orderId)
    {
        $this->getSession()->set(static::SESSION_TAG_LAST_COMPLETED_ORDER, $orderId);
        return $this;
    }
    
    /**
     * generateOrderNumber
     *
     * @param OrderInterface $order
     *
     * @return OrderInterface
     */
    public function generateOrderNumber(OrderInterface $order)
    {
        $orderNumberPrefix = $this->getConfigurationManager()->get('commerce.order.order_number_prefix');
        
        // visitor matches affiliate, and affiliate has order number prefix
        if($this->getAffiliateManager()->getCurrentAffiliateId()){
            $affiliate = $this->getAffiliateManager()->findAffiliateById($this->getAffiliateManager()->getCurrentAffiliateId());
        } else if($order->getVisitor() && $order->getVisitor()->getInitialReferer()){
            $affiliate = $this->getAffiliateManager()->findAffiliateByName($order->getVisitor()->getInitialReferer());
        }
        
        if(isset($affiliate) && $affiliate instanceof AffiliateInterface){
            if($affiliate->getOrderPrefix()){
                $orderNumberPrefix = $affiliate->getOrderPrefix();
            }
        }
        
        $replacements = array(
            '{prefix}' => $orderNumberPrefix,
            '{date}' => date($this->getConfigurationManager()->get('commerce.order.order_number_date_format', 'mdy')),
            '{id}' => $order->getId(),
            '{customerId}' => $order->getCustomer() ? $order->getCustomer()->getId() : null,
        );
        
        $order->setOrderNumber(str_ireplace(
            array_keys($replacements), 
            array_values($replacements), 
            $this->getConfigurationManager()->get('commerce.order.order_number_format', '{prefix}{date}{id}') 
        ));
        
        return $order;
    }
}

<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\WebService;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;

/**
 * OrderSoapService
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderSoapService
{
    /**
     * Constructors
     */
    public function __construct(
            ConfigurationManager $configurationManager, 
            OrderHelper $orderHelper, 
            EncryptionManager $encryptionManager,
            ContainerAwareEventDispatcher $eventDispatcher,
            EntityManager $em
    )
    {
        $this->configurationManager = $configurationManager;
        $this->orderHelper = $orderHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->encryptionManager = $encryptionManager;
        $this->em = $em;
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
     * getEventDispatcher
     *
     * @return ContainerAwareEventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
    
    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
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
     * getOrder
     * 
     * @param int $orderId
     * 
     * @return array
     */
    public function getOrder($orderId)
    {
        try{
            $order = $this->getEntityManager()
              ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
              ->getWebServiceQuery()
              ->where('_order.id = :order')
              ->setParameter('order', $orderId)
              ->getQuery()
              ->getSingleResult(Query::HYDRATE_ARRAY);
            
        } catch(NoResultException $e) {
            return json_encode(array('success' => false, 'error' => sprintf('Order %s Not Found',$orderId)));
        }
        
        return json_encode($order);
    }    
    
    /**
     * getOrders
     *
     * @param array $filters
     *
     * @return array
     */
    public function getOrders(array $filters)
    {
        $orderQuery = $this->getEntityManager()
          ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
          ->getWebServiceQuery();
            
        if(isset($filters['id'])){
            if(is_array($filters['id'])){
                $orderQuery->andWhere('_order.id IN (:orderIds)')
                  ->setParameter('orderIds', $filters['id']);
            } else {
                $orderQuery->andWhere('_order.id = :orderId')
                 ->setParameter('orderId', $filters['id']);
            }
        }
            
        if(isset($filters['orderStatus'])){
            if(is_array($filters['orderStatus'])){
                $orderQuery->andWhere('_order.orderStatus IN (:orderStatuses)')
                ->setParameter('orderStatuses', $filters['orderStatus']);
            } else {
                $orderQuery->andWhere('_order.orderStatus = :orderStatus')
                ->setParameter('orderStatus', $filters['orderStatus']);
            }
        }
        
        $orders = $orderQuery->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        
        return json_encode($orders);
    }
    
    /**
     * updateOrder
     * 
     * @param int $orderId
     * @param array $fields
     */
    public function updateOrder($orderId, array $fields)
    {
        return json_encode(array('success'=>false,'error' => 'Not Yet Implemented'));
        
        try{
            $order = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
            ->getWebServiceQuery()
            ->where('_order.id = :orderId')
            ->setParameter('orderId',$orderId)
            ->getQuery()
            ->getSingleResult();
        } catch(NoResultException $e) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Order Not Found'
            ));
        }
        
        $originalStatus = $order->getOrderStatus();
        
        foreach($fields as $fieldName => $fieldValue) {
            if(is_array($fieldValue)||is_object($fieldValue)){
                return json_encode(array(
                    'success' => false, 
                    'error' => sprintf('Field %s must be a string', $fieldName),
                ));
            }
            
            $setterMethod = 'set'.ucfirst($fieldName);
            
            if(!method_exists($order, $setterMethod)){
                return json_encode(array(
                    'success' => false,
                    'error' => sprintf('Order does not have field %s ', $fieldName),
                ));
            }
            
            $order->$setterMethod($fieldValue);
        }
        
        //$this->getEntityManager()->persist($order)
        //$this->getEntityManager()->flush()
    }
    
    /**
     * addShipmentMemo
     *
     * @param int $shipmentId
     * @param array $userMemo
     * @param bool $notifyCustomer
     */
    public function addShipmentMemo($shipmentId, array $userMemo, $notifyCustomer = true)
    {
        try{
            $order = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
            ->getWebServiceQuery()
            ->where('shipment.id = :shipmentId')
            ->setParameter('shipmentId',$shipmentId)
            ->getQuery()
            ->getSingleResult();
            
        } catch(NoResultException $e) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Shipment Not Found'
            ));
        }
        
        foreach(array(
            'createdBy',
            'memo',
            'trackingNumber') as $requiredField) {
            
            if(!isset($userMemo[$requiredField])){
                return json_encode(array(
                    'success' => false, 
                    'error' => sprintf('Memo data field %s is required',$requiredField)
                ));
            }
        }
        
        if( isset($userMemo['changedStatus']) 
            && !in_array($userMemo['changedStatus'],$this->getOrderHelper()->getAvailableStatuses())){
            return json_encode(array(
                'success' => false,
                'error' => sprintf('Status %s not a valid status.',$userMemo['changedStatus'])
            ));
        }
        
        if($order->getShipment()->hasTrackingNumber($userMemo['trackingNumber'])){
            return json_encode(array(
                'success' => false,
                'error' => sprintf('Order Shipment already has the tracking number %.',$userMemo['trackingNumber'])
            ));
        }

        $memo = $this->getConfigurationManager()
          ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_SHIPMENT_MEMO)
          ->setShipment($order->getShipment())
          ->setCreatedBy($userMemo['createdBy'])
          ->setMemo($userMemo['memo'])
          ->setTrackingNumber($userMemo['trackingNumber'])
          ->setPreviousStatus($order->getShipment()->getShipmentStatus())
          ->setChangedStatus(isset($userMemo['changedStatus']) ? $userMemo['changedStatus'] : OrderInterface::STATUS_SHIPPED);
          
          if(isset($userMemo['memoData'])){
              if(!is_array($userMemo['memoData'])){
                  $userMemo['memoData'] = array($userMemo['memoData']);
              }
              $memo->setMemoData($userMemo['memoData']);
          }
        
          $this->getEventDispatcher()->dispatch(
              Events\Event::EVENT_ORDER_SHIPMENT_UPDATE,
              new Events\OrderShipmentUpdateEvent($order, $memo)
          );
          
          return json_encode(array(
              'success' => true, 
              'memoId' => $memo->getId()
          ));
    }

    /**
     * addPaymentMemo
     *
     * @param int $paymentId
     * @param array $memo
     */
    public function addPaymentMemo($paymentId, array $userMemo)
    {
        try{
            $order = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
            ->getWebServiceQuery()
            ->where('payment.id = :paymentId')
            ->setParameter('paymentId',$paymentId)
            ->getQuery()
            ->getSingleResult();
        
        } catch(NoResultException $e) {
            return json_encode(array(
                'success' => false,
                'error' => 'Payment Not Found'
            ));
        }
        

        if($order->getPayment()->getPaymentStatus() == OrderInterface::STATUS_COMPLETE){
            return json_encode(array(
                'success' => false,
                'error' => 'Order Payment has already been recieved'
            ));
        }
        
        foreach(array(
          'createdBy',
          'memo',
          'amountPaid') as $requiredField) {                   
            if(!isset($userMemo[$requiredField])){
                return json_encode(array(
                    'success' => false, 
                    'error' => sprintf('Memo data field %s is required',$requiredField)
                ));
            }
        }
        
        if( isset($userMemo['changedStatus'])
        && !in_array($userMemo['changedStatus'],$this->getOrderHelper()->getAvailableStatuses())){
            return json_encode(array(
                'success' => false,
                'error' => sprintf('Status %s not a valid status.',$userMemo['changedStatus'])
            ));
        }

        
        $memo = $this->getConfigurationManager()
        ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_PAYMENT_MEMO)
        ->setPayment($order->getPayment())
        ->setCreatedBy($userMemo['createdBy'])
        ->setMemo($userMemo['memo'])
        ->setAmountPaid($userMemo['amountPaid'])
        ->setPreviousStatus($order->getPayment()->getPaymentStatus())
        ->setChangedStatus(isset($userMemo['changedStatus']) ? $userMemo['changedStatus'] : OrderInterface::STATUS_COMPLETE)
        ->setMerchantTransactionId(isset($userMemo['merchantTransactionId']) ? $userMemo['merchantTransactionId'] : null);
        
        if(isset($userMemo['memoData'])){
            if(!is_array($userMemo['memoData'])){
                $userMemo['memoData'] = array($userMemo['memoData']);
            }
            $memo->setMemoData($userMemo['memoData']);
        }
        
        $this->getEventDispatcher()->dispatch(
            Events\Event::EVENT_ORDER_PAYMENT_UPDATE,
            new Events\OrderPaymentUpdateEvent($order, $memo)
        );
        
        return json_encode(array(
            'success' => true, 
            'memoId' => $memo->getId()
        ));
    }


    /**
     * cancelOrder
     *
     * @param int $orderId
     *
     * @return array
     */
    public function cancelOrder($orderId)
    {
        try{
            $order = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
            ->getWebServiceQuery()
            ->where('_order.id = :order')
            ->setParameter('order', $orderId)
            ->getQuery()
            ->getSingleResult();
              
        } catch(NoResultException $e) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Order Not Found'
            ));
        }
    
        $this->getEventDispatcher()->dispatch(
            Events\Event::EVENT_ORDER_CANCEL,
            new Events\OrderUpdateEvent($order)
        );
        
        return json_encode(array(
            'success' => true
        ));
    }
    
    /**
     * returnOrder
     *
     * @param int $orderId
     *
     * @return array
     */
    public function returnOrder($orderId)
    {
        try{
            $order = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
            ->getWebServiceQuery()
            ->where('_order.id = :order')
            ->setParameter('order', $orderId)
            ->getQuery()
            ->getSingleResult();
             
        } catch(NoResultException $e) {
            return json_encode(array(
                'success' => false,
                'error' => 'Order Not Found'
            ));
        }
    
        $this->getEventDispatcher()->dispatch(
            Events\Event::EVENT_ORDER_RETURN,
            new Events\OrderUpdateEvent($order)
        );
    
        return json_encode(array(
            'success' => true
        ));
    }
    
    /**
     * getOrderDecryptedCreditCard
     */
    public function getOrderDecryptedCreditCard($orderId, $deleteAfter = false)
    {
        try{
            $order = $this->getEntityManager()
            ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ORDER))
            ->getWebServiceQuery()
            ->where('_order.id = :order')
            ->setParameter('order', $orderId)
            ->getQuery()
            ->getSingleResult();
             
        } catch(NoResultException $e) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Order Not Found'
            ));
        }
        
        $payment = $order->getPayment();
        
        
        if(!$payment || ! $payment->getCreditCard() || ! $payment->getCreditCard()->getCardNumber()){
            return json_encode(array(
                'success' => false,
                'error' => 'Order Has No Credit Card as Payment'
            ));
        }
              
        $return = json_encode(array(
            'success' => true,
            'cardNumber' => $this->getEncryptionManager()->decrypt($order->getProtectCode(), $order->getPayment()->getCreditCard()->getCardNumber()),
            'cardExpiration' => $order->getPayment()->getCreditCard()->getCardExpiration(),
            'cardCvv'=> $order->getPayment()->getCreditCard()->getCardCvv(),
        ));
        
        if(true === $deleteAfter) {
            $payment->getCreditCard()->setCardNumber(null);
            $this->getEntityManager()->persist($payment->getCreditCard());
            $this->getEntityManager()->flush();
        }
        
        return $return;
    }
}
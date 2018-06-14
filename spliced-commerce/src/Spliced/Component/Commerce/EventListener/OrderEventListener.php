<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace  Spliced\Component\Commerce\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Order\OrderManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * OrderEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OrderEventListener
{
    
    /**
     * 
     */
    public function __construct(ConfigurationManager $configurationManager, OrderManager $orderManager, OrderHelper $orderHelper, EngineInterface $templating, \Swift_Mailer $mailer)
    {
        $this->configurationManager = $configurationManager;
        $this->orderManager = $orderManager;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->orderHelper = $orderHelper;
    }
    
    /**
     * getOrderManager
     *
     * @return OrderManager
     */
    protected function getOrderManager()
    {
        return $this->orderManager;
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
     * getTemplating
     * 
     * @return TwigEngine
     */
    protected function getTemplating()
    {
        return $this->templating;    
    }
    
    /**
     * getMailer
     * 
     * @return Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
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
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * onOrderUpdate
     */
    public function onOrderUpdate(Events\OrderUpdateEvent $event)
    {
        $order = $event->getOrder();
        
        $paymentStatus = $order->getPayment()->getPaymentStatus();
        $shipmentStatus = $order->getShipment()->getShipmentStatus();
        
        
        if($paymentStatus == OrderInterface::STATUS_COMPLETE
            && in_array($shipmentStatus, array(OrderInterface::STATUS_COMPLETE,OrderInterface::STATUS_SHIPPED))){
            
            $order->setOrderStatus(OrderInterface::STATUS_COMPLETE);
        }
        
        if($paymentStatus == OrderInterface::STATUS_COMPLETE
            && in_array($shipmentStatus, array(OrderInterface::STATUS_PENDING,OrderInterface::STATUS_PROCESSING,OrderInterface::STATUS_INCOMPLETE))){
            
            $order->setOrderStatus(OrderInterface::STATUS_PROCESSING);

        }

        
        foreach($order->getShipment()->getMemos() as $shipmentMemo) {
            if(!$shipmentMemo->getCustomerNotified() && $shipmentMemo->getTrackingNumber()){
                
                $event->getDispatcher()->dispatch(
                    Events\Event::EVENT_ORDER_SHIPPED,
                    new Events\OrderShipmentUpdateEvent($order, $shipmentMemo)
                ); 
                
            }
        }
        
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }
    
    /**
     * onOrderShipped
     * 
     * @param Events\OrderShipmentUpdateEvent $event
     */
    public function onOrderShipped(Events\OrderShipmentUpdateEvent $event)
    {
        if(!$event->getMemo()->getCustomerNotified() && $event->getMemo()->getTrackingNumber()){ 
            if($this->getConfigurationManager()->get('commerce.sales.email.shipment.notify')){
                // notify customer
                $notificationMessage = \Swift_Message::newInstance()
                ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.shipment.subject'),$event->getOrder()))
                ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
                ->setTo($event->getOrder()->getEmail())
                ->setBody($this->getTemplating()->render('SplicedCommerceAdminBundle:Email:order_shipment.html.twig', array(
                    'order' => $event->getOrder(),
                    'memo' => $event->getMemo()
                )), 'text/html')
                ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));

                if($this->getMailer()->send($notificationMessage)){
                    $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
                    ->setOrder($event->getOrder())
                    ->setCreatedBy('system')
                    ->setNotificationType('shipment_update')
                    ->setMemo('Order Shipment Update Confirmation Email Sent');
                
                    $this->getEntityManager()->persist($orderMemo);
                    
                    $event->getMemo()->setCustomerNotified(true);
                }
                
                
            }
        }
    }
    
    /**
     * onOrderShipmentUpdate
     * 
     * @param Events\OrderShipmentUpdateEvent $event
     */
    public function onOrderShipmentUpdate(Events\OrderShipmentUpdateEvent $event)
    {
        $order = $event->getOrder();
        $memo  = $event->getMemo();

        if($memo->getChangedStatus() == OrderInterface::STATUS_COMPLETE){
            $memo->setChangedStatus(OrderInterface::STATUS_SHIPPED);
        }
        
        if($memo->getChangedStatus() == OrderInterface::STATUS_SHIPPED && !$memo->getTrackingNumber()){
            $memo->setChangedStatus(OrderInterface::STATUS_PROCESSING);
        }
 
        $order->getShipment()->setShipmentStatus($memo->getChangedStatus());
        $order->getShipment()->addMemo($memo);
        
        $memo->setShipment($order->getShipment());
        
        $event->getDispatcher()->dispatch(
            Events\Event::EVENT_ORDER_UPDATE,
            new Events\OrderUpdateEvent($order)
        );
    }

    /**
     * @param Events\OrderPaymentUpdateEvent $event
     */ 
    public function onOrderPaymentUpdate(Events\OrderPaymentUpdateEvent $event)
    {
        $order = $event->getOrder();
        $memo  = $event->getMemo();

        $orderTotal = $this->getOrderHelper()->getOrderTotal($order);

        // TODO: Review this, make sure we calculate correctly to match payment amounts to order totals
        // ensuring all is paid!
        // amountPaid should be an amount set on the memo
        // by the backend user, merchant, or 3rd party API
        if($memo->getChangedStatus() == OrderInterface::STATUS_COMPLETE && $orderTotal != $memo->getAmountPaid()){
            if($orderTotal > $memo->getAmountPaid()){
                $memo->setChangedStatus(OrderInterface::STATUS_PARTIALLY_PAYED);
            }
        }
        
        if($order->getPayment()->getPaymentStatus() != OrderInterface::STATUS_COMPLETE && $orderTotal == $memo->getAmountPaid()){
            $memo->setChangedStatus(OrderInterface::STATUS_COMPLETE);
        }
        
        $order->getPayment()->setPaymentStatus($memo->getChangedStatus());
        $order->getPayment()->addMemo($memo);
        
        $memo->setPayment($order->getPayment());
        
        if($order->getPayment()->getPaymentStatus() == OrderInterface::STATUS_COMPLETE
            && $this->getConfigurationManager()->get('commerce.sales.email.payment.notify')){
            // notify customer
            $notificationMessage = \Swift_Message::newInstance()
            ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.payment.subject'),$event->getOrder()))
            ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
            ->setTo($event->getOrder()->getEmail())
            ->setBody($this->getTemplating()->render('SplicedCommerceAdminBundle:Email:order_payment.html.twig', array(
                    'order' => $event->getOrder(),
            )), 'text/html')
            ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));

            if($this->getMailer()->send($notificationMessage)){
                $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
                ->setOrder($event->getOrder())
                ->setCreatedBy('system')
                ->setNotificationType('payment_update')
                ->setMemo('Order Payment Update Confirmation Email Sent');
            
                $this->getEntityManager()->persist($orderMemo);
            }
        }
        
        $event->getDispatcher()->dispatch(
            Events\Event::EVENT_ORDER_UPDATE,
            new Events\OrderUpdateEvent($order)
        );
    }
    
    /**
     * onOrderReturn
     */
    public function onOrderReturn(Events\OrderUpdateEvent $event)
    {
    
        $event->getOrder()->setOrderStatus(OrderInterface::STATUS_RETURNED);
        
        //TODO: Handle any payment cancellations, if set it config
        if($event->getOrder()->getPayment()->getPaymentStatus() == OrderInterface::STATUS_COMPLETE){
            $event->getOrder()->getPayment()->setPaymentStatus(OrderInterface::STATUS_REFUNDED);
        } else {
            $event->getOrder()->getPayment()->setPaymentStatus(OrderInterface::STATUS_CANCELLED);
        }
        
        $event->getOrder()->getShipment()->setShipmentStatus(OrderInterface::STATUS_CANCELLED);
        
        $this->getEntityManager()->persist($event->getOrder());
    
                
        if($this->getConfigurationManager()->get('commerce.sales.email.return.notify')){
            // notify customer
            $notificationMessage = \Swift_Message::newInstance()
            ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.return.subject'),$event->getOrder()))
            ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
            ->setTo($event->getOrder()->getEmail())
            ->setBody($this->getTemplating()->render('SplicedCommerceAdminBundle:Email:order_return.html.twig', array(
                'order' => $event->getOrder(),
            )), 'text/html')
            ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));
        
            if($this->getMailer()->send($notificationMessage)){
                $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
                  ->setOrder($event->getOrder())
                  ->setCreatedBy('system')
                  ->setNotificationType('order_return')
                  ->setMemo('Order Return Confirmation Email Sent');
                
                $this->getEntityManager()->persist($orderMemo);
            }
        }
        
        $this->getEntityManager()->flush();
    }
    
    /**
     * onOrderCancel
     */
    public function onOrderCancel(Events\OrderUpdateEvent $event)
    {

        $event->getOrder()->setOrderStatus(OrderInterface::STATUS_CANCELLED);
        $event->getOrder()->getPayment()->setPaymentStatus(OrderInterface::STATUS_CANCELLED);
        $event->getOrder()->getShipment()->setShipmentStatus(OrderInterface::STATUS_CANCELLED);

        $this->getEntityManager()->persist($event->getOrder());

        if($this->getConfigurationManager()->get('commerce.sales.email.cancellation.notify')){        
            // notify customer
            $notificationMessage = \Swift_Message::newInstance()
            ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.cancellation.subject'),$event->getOrder()))
            ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
            ->setTo($event->getOrder()->getEmail())
            ->setBody($this->getTemplating()->render('SplicedCommerceAdminBundle:Email:order_cancellation.html.twig', array(
                'order' => $event->getOrder(),
            )), 'text/html')
            ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));
             
            if($this->getMailer()->send($notificationMessage)){
                $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
                  ->setOrder($event->getOrder())
                  ->setCreatedBy('system')
                  ->setNotificationType('order_cancellation')
                  ->setMemo('Order Cancellation Confirmation Email Sent');
                
                $this->getEntityManager()->persist($orderMemo);
            }
        }
        
        $this->getEntityManager()->flush();
    }
     
    /**
     * onOrderMemo
     */
    public function onOrderMemo(Events\OrderMemoEvent $event)
    {
        
    }
    
    /**
     * onIncompleteOrderFollowupNotification
     */
    public function onIncompleteOrderFollowupNotification(Events\OrderUpdateEvent $event)
    {
        // notify customer if they have an email
        if($event->getOrder()->getEmail()){
            $notificationMessage = \Swift_Message::newInstance()
            ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.incomplete_order_followup.subject'),$event->getOrder()))
            ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
            ->setTo($event->getOrder()->getEmail())
            ->setBody($this->getTemplating()->render('SplicedCommerceAdminBundle:Email:incomplete_order.html.twig', array(
                'order' => $event->getOrder(),
            )), 'text/html')
            ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));
            
            if($this->getMailer()->send($notificationMessage)){
                $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
                ->setOrder($event->getOrder())
                ->setCreatedBy('system')
                ->setNotificationType('incomplete_order_followup')
                ->setMemo('Incomplete Order Followup Email Sent');
             
                $this->getEntityManager()->persist($orderMemo);
                $this->getEntityManager()->flush();
            }
        }
    }
    
    /**
     * replaceEmailSubject
     * 
     * @param string $subject
     * @param OrderInterface $order
     */
    protected function replaceEmailSubject($subject, OrderInterface $order)
    {
        $replacements = array(
            '{orderNumber}' => $order->getOrderNumber(),
            '{firstName}' => $order->getBillingFirstName(),
            '{lastName}' => $order->getBillingLastName(),
            '{email}' => $order->getEmail(),
        ); 
        return str_replace(array_keys($replacements),array_values($replacements), $subject);
    }
}
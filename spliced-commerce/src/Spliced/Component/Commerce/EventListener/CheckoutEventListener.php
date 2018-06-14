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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Spliced\Component\Commerce\Cart\CartManager;
use Symfony\Component\Security\Core\SecurityContext;
use Spliced\Component\Commerce\Product\ProductPriceHelper;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model;
use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;
use Spliced\Component\Commerce\Visitor\VisitorManager;
use Spliced\Component\Commerce\Logger\Logger;
use Spliced\Component\Commerce\Order\OrderManager;
use Spliced\Component\Commerce\Routing\Router;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

/**
 * CheckoutEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutEventListener
{
    public function __construct(
        ConfigurationManager $configurationManager,
        CheckoutManager $checkoutManager, 
        OrderManager $orderManager,
        CartManager $cartManager,
        SecurityContext $securityContext, 
        ProductPriceHelper $priceHelper,  
        Session $session, 
        \Swift_Mailer $mailer, 
        EngineInterface $templating,
        Router $router,
        EncryptionManager $encryptionManager, 
        VisitorManager $visitorManager, 
        Logger $logger
    )
    {
        $this->checkoutManager = $checkoutManager;
        $this->orderManager = $orderManager;
        $this->cartManager = $cartManager;
        $this->securityContext = $securityContext;
        $this->priceHelper = $priceHelper;
        $this->session = $session;
        $this->configurationManager = $configurationManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->encryptionManager = $encryptionManager;
        $this->logger = $logger;
        $this->visitorManager = $visitorManager;
        $this->router = $router;
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
     * getRouter
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->router;
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
     * getSecurityContext
     *
     * @return SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * getProductPriceHelper
     *
     * @return ProductPriceHelper
     */
    protected function getProductPriceHelper()
    {
        return $this->priceHelper;
    }
    
    /**
     * getVisitorManager
     *
     * @return VisitorManager
     */
    protected function getVisitorManager()
    {
        return $this->visitorManager;
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
     * getCartManager
     *
     * @return CartManager
     */
    protected function getCartManager()
    {
        return $this->cartManager;
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
     * getSession
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
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
     * getTemplating
     *
     * @return TwigEngine
     */
    protected function getTemplating()
    {
        return $this->templating;
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
        return $this->getConfigurationManager()->getEntityManager();
    }

    /**
     * onCheckoutStart
     * 
     * This event is fired when the checkout process is first started
     *
     * @param CheckoutEvent
     */
    public function onCheckoutStart(Events\CheckoutEvent $event)
    {
        $order = $event->getOrder();
        
        if (!$order->getVisitor() && $this->getVisitorManager()->getCurrentVisitor() !== false) {
            
            $order->setVisitor($this->getVisitorManager()->getCurrentVisitor());
                        
        }
        
        $this->getOrderManager()->updateOrderItems($order, false);
        
        $this->getOrderManager()->updateOrder($order);
        
    }
    
    /**
     * onCheckoutProcessStep
     * 
     * This event should be fired to process the current step
     *
     * @param CheckoutProcessStepEvent
     */
    public function onCheckoutProcessStep(Events\CheckoutProcessStepEvent $event)
    {
        $currentStep = $this->getCheckoutManager()->getCurrentStep();
        
        $moveStepEvent = null;
        
        foreach($this->getCheckoutManager()->getStepHandlersByStep($currentStep) as $stepHandler) {
            $result = $stepHandler->process($event->getForm(), $event->getRequest());

            // If the result is an instance of a response and it has not been set yet
            // lets set the response. We assume that in this case the step has not been 
            // submitted yet or requires attention to complete it
            if($result instanceof Response && !$event->getResponse()){
                $event->setResponse($result);
            } 
             
            // if the result is an instanceof OrderInterface we 
            // assume the step has been processed and is complete
            if($result instanceof Events\CheckoutMoveStepEvent) {
                $event->setComplete(true);
                $moveStepEvent = $result;
            }
        }

        if($event->isComplete() && $moveStepEvent instanceof Events\CheckoutMoveStepEvent) {
            // the current step that has been processed is the final step?
            if($this->getCheckoutManager()->getLastStep() == $result->getCurrentStep()){
                $event->setLastStep(true);
            }
                        
            $event->getDispatcher()->dispatch(
                Events\Event::EVENT_CHECKOUT_MOVE_STEP,
                $moveStepEvent
            );
        } else if(!$event->getResponse()) {
            throw new \LogicException('The processed step did not return an instance of Response or an instance of CheckoutMoveStepEvent');
        }
    }    
    
    /**
     * onCheckoutMoveStep
     * 
     * This event is fired after onCheckoutProcessStep has been processed and is 
     * determined to be complete
     */
    public function onCheckoutMoveStep(Events\CheckoutMoveStepEvent $event)
    {
        $order = $event->getOrder();

        $this->getOrderManager()->updateOrder($order);
        
        if ($this->getCheckoutManager()->getLastCompletedStep() < $this->getCheckoutManager()->getCurrentStep()) {
            $this->getCheckoutManager()->setLastCompletedStep($this->getCheckoutManager()->getCurrentStep());
        }
        
        if($this->getCheckoutManager()->getCurrentStep() != $this->getCheckoutManager()->getLastStep()){
            // update checkout session to next step only if it is not the last step
            $this->getCheckoutManager()->setCurrentStep($this->getCheckoutManager()->getCurrentStep()+1);
        }
    }

    /**
     * onCheckoutFinalize
     *
     * @param CheckoutEvent
     */
    public function onCheckoutFinalize(Events\CheckoutFinalizeEvent $event)
    {
        $order = $event->getOrder();
        $request = $event->getRequest();
        
        //process payment and fire complete event or handle remote redirect
        try {
            $paymentProvider = $this->getCheckoutManager()->getPaymentProvider(
                $order->getPayment()->getPaymentMethod()
            );
            
            if($paymentProvider->isRemotelyProcessed()){
                $redirectResponse = $paymentProvider->startRemotePayment($order);
                
                if(!$redirectResponse instanceof RedirectResponse) {
                       
                    if ($request->isXmlHttpRequest()) {
                        $event->setResponse(new JsonResponse(array(
                            'error' => 'Payment Provider did not return a redirect response',
                            'success' => false,
                            'message' => 'An error occoured while processing your payment.',
                        )));
                     
                    } else {
                        $this->getSession()->getFlashBag()->add('error','An error occoured while processing your payment. ');
                    }
                    
                } else {
                
                    $event->getDispatcher()->dispatch(
                        Events\Event::EVENT_REMOTELY_PROCESSED_CHECKOUT_START,
                        new Events\RemotelyProcessedCheckoutStartEvent($order)
                    );
                    
                    $event->setResponse($redirectResponse);                
                }
                
            } else {
                $order = $paymentProvider->process($order);
                $order = $this->getCheckoutManager()->generateOrderNumber($order);
                
                $event->getDispatcher()->dispatch(
                    Events\Event::EVENT_CHECKOUT_COMPLETE,
                    new Events\CheckoutCompleteEvent($order, $request)
                );
                
                $event->setComplete(true);
            }
            
        } catch (PaymentErrorException $e) {
            
            // some other payment error occoured
            $event->getDispatcher()->dispatch(
                Events\Event::EVENT_CHECKOUT_PAYMENT_ERROR,
                new Events\CheckoutPaymentErrorEvent($e->getOrder())
            );
            
            $this->getLogger()->error(sprintf('Order %s | Events\Event::EVENT_CHECKOUT_PAYMENT_ERROR: %s', $order->getId(), $e->getMessage()));
            
            if ($request->isXmlHttpRequest()) {
                $event->setResponse(new JsonResponse(array(
                    'error' => $e->getMessage(),
                    'success' => false,
                    'message' => 'An error occoured while processing your payment.',
                )));
             
            } else {
                $this->getSession()->getFlashBag()->add('error','An error occoured while processing your payment. ');
            }
            
        } catch (PaymentDeclinedException $e) {
            // does the payment method want us to allow a successful 
            // checkout even if we declined? if not lets redirect to 
            // payment method section of checkout
            if ($paymentProvider->getOption('success_on_decline')) {
                $event->getDispatcher()->dispatch(
                   Events\Event::EVENT_CHECKOUT_COMPLETE_ON_DECLINE,
                   new Events\CheckoutCompleteOnDeclineEvent($order, $paymentProvider)
                );
                $this->getLogger()->info(sprintf('Order %s | Order Completed on Declined Payment', $order->getId()));
            } else {
                 
                $this->getLogger()->error(sprintf('Order %s | Order Payment Decline', $order->getId()));
                    
                if ($request->isXmlHttpRequest()) {
                    $event->setResponse(new JsonResponse(array(
                        'success' => false,
                        'message' => 'Your Payment Has Been Declined',
                        'error' => $e->getMessage(),
                    )));
                }
            
                $this->getSession()->getFlashBag()->add('error','Your payment has been declined.');
            }    
        }
    }
    
    /**
     * onRemotelyProcessedCheckoutStart
     *
     * @param CheckoutEvent
     */
    public function onRemotelyProcessedCheckoutStart(Events\RemotelyProcessedCheckoutStartEvent $event)
    {
        
        $order = $event->getOrder();

        $this->getOrderManager()->updateOrderItems($order, false);
         
        $this->getOrderManager()->updateOrder($order);
        
    }
    

    
    /**
     * onCheckoutComplete
     * 
     * Fires when checkout has been completed, called before onCheckoutSuccess
     *
     * @param CheckoutEvent $event
     */
    public function onCheckoutComplete(Events\CheckoutEvent $event)
    {
        
       $order = $event->getOrder();
       $request = $event->getRequest();
       
       $order->setFinishIp($request->getClientIp())
            ->setCompletedAt(new \DateTime());
        
        // send out notification
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.confirmation.subject'), $order))
        ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
        ->setTo($order->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.confirmation', 'SplicedCommerceBundle:Email:order_confirmation_customer.html.twig'), array(
            'order' => $order
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.confirmation_plain', 'SplicedCommerceBundle:Email:order_confirmation_customer.txt.twig'), array(
            'order' => $order
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));

        if($this->getMailer()->send($notificationMessage)){
            $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
            ->setOrder($event->getOrder())
            ->setCreatedBy('system')
            ->setNotificationType('order_confirmation_admin')
            ->setMemo('Order Confirmation Email Sent');
        
            $order->addMemo($orderMemo);
        }
     
        // send out notification to admin if email is set in config
        if ($this->getConfigurationManager()->get('commerce.sales.email.to_admin')) {
            $adminNotificationMessage = \Swift_Message::newInstance()
            ->setSubject($this->replaceEmailSubject($this->getConfigurationManager()->get('commerce.sales.email.confirmation_admin.subject'),$event->getOrder()))
            ->setFrom($this->getConfigurationManager()->get('commerce.sales.email.from'))
            ->setTo($this->getConfigurationManager()->get('commerce.sales.email.to_admin'))
            ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.confirmation_admin', 'SplicedCommerceBundle:Email:order_confirmation_admin.html.twig'), array(
                'order' => $order,
            )), 'text/html')
            ->setReturnPath($this->getConfigurationManager()->get('commerce.sales.email.bounced'));

            if($this->getMailer()->send($adminNotificationMessage)){
                $orderMemo = $this->getConfigurationManager()->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_MEMO)
                ->setOrder($order)
                ->setCreatedBy('system')
                ->setNotificationType('order_confirmation_admin')
                ->setMemo('Admin Order Confirmation Email Sent');
                 
                $order->addMemo($orderMemo);
            }
        }

        // reset checkout session data and cart data
        $this->getCartManager()->reset(false);
         
        $this->getOrderManager()->updateOrder($event->getOrder());
    }
    
    /**
     * onCheckoutSuccess
     * 
     * Fires when the success page is viewed, called after onCheckoutComplete
     *
     * @param CheckoutEvent $event
     */
    public function onCheckoutSuccess(Events\CheckoutEvent $event)
    {
        $this->getCheckoutManager()->reset($event->getOrder()->getId());
    }
    
    /**
     * onPaymentError
     *
     * @param CheckoutPaymentErrorEvent $event
     */
    public function onPaymentError(Events\CheckoutPaymentErrorEvent $event)
    {
        // persist the order to save any memos, etc
        $this->getOrderManager()->updateOrder($event->getOrder());
    }
    
    /**
     * onPaymentMethodChange
     *
     * @param CheckoutEvent $event
     */
    public function onPaymentMethodChange(Events\CheckoutEvent $event)
    {
        $cartManager     = $this->getCartManager();
        $checkoutManager = $this->getCheckoutManager();
        $securityContext = $this->getSecurityContext();
        $em              = $this->getEntityManager();

        $order       = $event->getOrder();
        $payment     = $order->getPayment();

        $paymentMethod = $checkoutManager->getPaymentProvider($payment->getPaymentMethod());

        if ($payment && !$payment->getId()) {
            $payment->setOrder($order)
              ->setCreatedAt(new \DateTime('now'))
              ->setUpdatedAt(new \DateTime('now'));
        }

        if($this->request->query->has('reset') && $payment->getCreditCard()) {
            $payment->getCreditCard()
              ->setCardNumber(null)
              ->setLastFour(null)
              ->setCardCvv(null)
              ->setCardExpiration(null); 
        }
        
        if ($paymentMethod->acceptsCreditCards() === false && $payment->getCreditCard()) {
            // remove credit card record if changed to non-credit card
            $creditCard = $payment->getCreditCard();
            $em->remove($creditCard);
            $payment->setCreditCard(null);
        } elseif ($payment->getCreditCard() && !$payment->getCreditCard()->getPayment()) {
            $creditCard = $payment->getCreditCard();
            $creditCard->setPayment($payment);
            if ($securityContext->isGranted('ROLE_USER')) {
                $creditCard->setCustomer($securityContext->getToken()->getUser());
            }
            $em->persist($creditCard);
        }

        $em->persist($payment);
        $em->flush();

    }

    /**
     * onCheckoutNextStep
     * 
     * @param Event\CheckoutEvent $event
     */
    public function onCheckoutNextStep(Events\CheckoutEvent $event)
    {
        $cartManager     = $this->getCartManager();
        $checkoutManager = $this->getCheckoutManager();
        $securityContext = $this->getSecurityContext();
        $priceHelper     = $this->getProductPriceHelper();
        $em                 = $this->getEntityManager();
        $configurationManager = $this->getConfigurationManager();
        $session = $this->getSession();

        $order         = $event->getOrder();
        $shipment     = $order->getShipment();
        $payment     = $order->getPayment();
        $items         = $order->getItems();

        
        try {
            if (!$order->getVisitor()) {
                try {
                    if($this->getSession()->has('commerce.visitor_id')){
                        $visitor = $this->getEntityManager()
                          ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_VISITOR))
                          ->findOneById($this->getSession()->get('commerce.visitor_id'));
                    } else {
                        $visitor = $this->getEntityManager()
                          ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_VISITOR))
                          ->findOneBySessionId($this->getSession()->getId());
                    }

                    $order->setVisitor($visitor);
                    
                } catch (NoResultException $e) { /* Do Nothing */ }
            }

            if (!$order->hasAlternateShippingAddress()) {
                // set the shipping address as the billing if we do not have an alternate address provided
                $order->setShippingName($order->getBillingFullName());
                $order->setShippingAddress($order->getBillingAddress());
                $order->setShippingAddress2($order->getBillingAddress2());
                $order->setShippingCity($order->getBillingCity());
                $order->setShippingState($order->getBillingState());
                $order->setShippingZipcode($order->getBillingZipcode());
                $order->setShippingCountry($order->getBillingCountry());
                $order->setShippingPhoneNumber($order->getBillingPhoneNumber());
            }
    
            if ($shipment instanceof Model\OrderShipmentInterface) {
                
                if (!$shipment->getId()) {
                    $shipment->setOrder($order);
                }
                
                if(!$shipment->getCreatedAt() instanceof \DateTime){
                    $shipment->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime());
                }
                
                if($shipment->getUserSelection()){
                    
                    $shippingMethod = $checkoutManager->getShippingManager()->getMethodByFullName($shipment->getUserSelection());
                    $shippingProvider = $shippingMethod->getProvider();
                    
                    $shipment->setShipmentProvider($shippingProvider->getName())
                      ->setShipmentMethod($shippingMethod->getName());
                    
                } else if($shipment->getShipmentProvider() && $shipment->getShipmentMethod()){
                    $shippingProvider = $checkoutManager->getShippingProvider($shipment->getShipmentProvider());
                    $shippingMethod = $shippingProvider->getMethod($shipment->getShipmentMethod());
                    
                    $shipment->setShipmentProvider($shippingProvider->getName())
                      ->setShipmentMethod($shippingMethod->getName());
                } else {
                    $this->getLogger()->error('No Shipping Method Selected!');
                }                                        

                $shipment->setShipmentCost($shippingMethod->getPrice());
                $shipment->setShipmentPaid($shippingMethod->getPrice());
                
                $em->persist($shipment);
            }

            if ($payment instanceof Model\OrderPaymentInterface) {
                $creditCard = $payment->getCreditCard();

                if (!$payment->getId()) {
                    $payment->setOrder($order);                    
                }
                
                if(!$payment->getCreatedAt() instanceof \DateTime){
                    $payment->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime());
                }

                if ($creditCard instanceof Model\CustomerCreditCardInterface) {
                    if (!$creditCard->getId()) {
                        $creditCard->setPayment($payment);
                    }

                    if ($securityContext->isGranted('ROLE_USER')) {
                        $creditCard->setCustomer($securityContext->getToken()->getUser());
                    }

                    $cardLastFour = substr($creditCard->getCardNumber(),strlen($creditCard->getCardNumber())-4);

                    if ($cardLastFour != $creditCard->getLastFour() && !$creditCard->isEncrypted()) {
                        $creditCard->setLastFour($cardLastFour);
                    }

                    // encrypt card if not ecrypted
                    if ($creditCard->getCardNumber() && !$creditCard->isEncrypted()) {
                        $encryptedCardNumber = $this->getEncryptionManager()->encrypt($order->getProtectCode(), $creditCard->getCardNumber());
                        $creditCard->setCardNumber($encryptedCardNumber);
                    }

                    $em->persist($payment);
                    $em->persist($creditCard);
                }
            }

            if (!$order->getCustomer() && $securityContext->isGranted('ROLE_USER')) {
                $order->setCustomer($securityContext->getToken()->getUser());
            }

            if (!$order->getEmail() && $securityContext->isGranted('ROLE_USER')) {
                $order->setEmail($securityContext->getToken()->getUser()->getEmail());
            }
            
            // check for item changes in cart
            $this->getOrderManager()->updateOrderItems($order, false);
            
            // handle custom fields on step
            $customFields = $checkoutManager->getCustomFieldManager()->getFieldsByStep($checkoutManager->getCurrentStep());
            
            if(count($customFields)){
                $customFieldValues = $order->getCustomFieldValues();
                if(count($customFieldValues)) {
                    foreach($customFieldValues as $field => $value) {
                        if(!preg_match('/_params$/',$field)) {
                            if($order->hasCustomField($field)){
                                $customField = $order->getCustomField($field);
                                $customField->setFieldValue($value);
                            } else {
                                $customField = $configurationManager->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_CUSTOM_FIELD_VALUE)
                                  ->setOrder($order)
                                  ->setField($checkoutManager->getCustomFieldManager()->getFieldByName($field))
                                  ->setFieldValue($value);
                            }
                            $this->getEntityManager()->persist($customField);
                        }
                    }
                }
            }
            
            if($order->getSaveBillingAddress() && $securityContext->isGranted('ROLE_USER')){
                
                $savedBillingAddress = $configurationManager->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER_ADDRESS)
                  ->setCustomer($securityContext->getToken()->getUser())
                  ->setAddressLabel(null)
                  ->setFirstName($order->getBillingFirstName())
                  ->setLastName($order->getBillingLastName())
                  ->setAddress($order->getBillingAddress())
                  ->setAddress2($order->getBillingAddress2())
                  ->setCity($order->getBillingCity())
                  ->setState($order->getBillingState())
                  ->setZipcode($order->getBillingZipcode())
                  ->setCountry($order->getBillingCountry())
                  ->setPhoneNumber($order->getBillingPhoneNumber());
                
                if(!$securityContext->getToken()->getUser()->hasSavedAddress($savedBillingAddress)){
                    $em->persist($savedBillingAddress);
                }
                
                $order->setSaveBillingAddress(false);
                
            } else if($event->hasNewCustomerRegistration()){
                if($event->getCustomer()->getSaveAddress()){
                    $order->setSaveBillingAddress(true); // for next step
                    
                    if($order->hasAlternateShippingAddress()){ // for next step
                        $order->setSaveShippingAddress(true);
                    }
                }
            }
            
            if($order->getSaveShippingAddress() && $securityContext->isGranted('ROLE_USER')){
                // try to get their first and last name best we can
                $name = explode(' ', $order->getShippingName());
                if(count($name) > 2){
                    $firstName = $name[0];
                    unset($name[0]);
                    $lastName = implode(' ', $name);
                } else if(count($name) == 2){
                    $firstName = $name[0];
                    $lastName = $name[1];
                } else {
                    $firstName = $name[0];
                    $lastName = '';
                }
                
                $savedShippingAddress = $configurationManager->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER_ADDRESS)
                ->setCustomer($securityContext->getToken()->getUser())
                ->setAddressLabel(null)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setAddress($order->getShippingAddress())
                ->setAddress2($order->getShippingAddress2())
                ->setCity($order->getShippingCity())
                ->setState($order->getShippingState())
                ->setZipcode($order->getShippingZipcode())
                ->setCountry($order->getShippingCountry())
                ->setPhoneNumber($order->getShippingPhoneNumber());
                
                if(!$securityContext->getToken()->getUser()->hasSavedAddress($savedShippingAddress)){
                    $em->persist($savedShippingAddress);
                }
                
                $order->setSaveShippingAddress(false);
            }
            
            if ($checkoutManager->getCurrentStep() == CheckoutManager::STEP_REVIEW) {
                $order->setFinishIp($this->getRequest()->getClientIp())
                ->setCompletedAt(new \DateTime());
            }

            $em->persist($order);
            $em->flush();

            if ($checkoutManager->getLastCompletedStep() < $checkoutManager->getCurrentStep()) {
                $checkoutManager->setLastCompletedStep($checkoutManager->getCurrentStep());
            }

            // update checkout session to next step
            $checkoutManager->setCurrentStep($checkoutManager->getCurrentStep()+1);

        } catch (\Exception $e) {
            $this->getLogger()->exception(sprintf('Exception Caught During Checkout Step Event: %s - %s',
                get_class($e),
                $e->getMessage()
            ));
        }
    }

    /**
     * checkoutCompleteOnDeclineEvent
     */
    public function checkoutCompleteOnDeclineEvent(Events\CheckoutCompleteOnDeclineEvent $event)
    {
        $order = $event->getOrder();

        $this->getCheckoutManager()->generateOrderNumber($order);

        $order->setOrderStatus($event->getPaymentProvider()->getOption('success_on_decline_status'));

    }
    
    /**
     * replaceEmailSubject
     *
     * @param string $subject
     * @param OrderInterface $order
     */
    protected function replaceEmailSubject($subject, Model\OrderInterface $order)
    { 
        $replacements = array(
            '{orderNumber}' => $order->getOrderNumber(),
            '{firstName}' => $order->getBillingFirstName(),
            '{lastName}' => $order->getBillingLastName(),
            '{email}' => $order->getEmail(),
            '{orderStatus}' => ucwords($order->getOrderStatus()),        
            '{paymentStatus}' => ucwords($order->getPayment()->getPaymentStatus()),  
        	'{storeName}' => $this->getConfigurationManager()->get('commerce.store.name'),
        );
        return str_replace(
            array_keys($replacements),
            array_values($replacements), 
            $subject
        );
    }
}
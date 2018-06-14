<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout;

use Spliced\Bundle\CommerceBundle\Form\Type as Forms;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\AffiliateInterface;
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Payment\PaymentManager;
use Spliced\Component\Commerce\Shipping\ShippingManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Affiliate\AffiliateManager;
use Spliced\Component\Commerce\Order\OrderManager;

/**
 * CheckoutManager
 * 
 * Handles most of the checkout process.
 * 
 * Handles the registering of steps and also to create the form
 * for the current context of the checkout process.
 *  *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutManager
{
    /* @var PaymentManager */
    protected $paymentMethods;
    /* @var ShippingManager */
    protected $shippingManager;
    /* @var Session */
    protected $session;
    /* @var ConfigurationManager */
    protected $configurationManager;
    /* @var SecurityContext */
    protected $securityContext;

    const SESSION_TAG_CURRENT_ORDER         = 'commerce.cart.current_order_id';
    const SESSION_TAG_CURRENT_STEP          = 'commerce.cart.current_step';
    const SESSION_TAG_LAST_COMPLETED_STEP      = 'commerce.cart.last_completed_step';
    const SESSION_TAG_LAST_COMPLETED_ORDER     = 'commerce.cart.last_completed_order';

    /* Checkout Step Constants */
    const STARTING_STEP_POSITION = 1;

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param CartManager          $cartManager
     * @param OrderManager $orderManager
     * @param PaymentManager       $paymentManager
     * @param ShippingManager      $shippingManager
     * @param Session              $shippingManager
     * @param SecurityContext      $securityContext
     * @param CheckoutCustomFieldManager $customFieldManager
     * @param AffiliateManager     $affiliateManager
     * @param FormBuilder          $formFactory
     */
    public function __construct(ConfigurationManager $configurationManager, OrderManager $orderManager, CartManager $cartManager, PaymentManager $paymentManager, ShippingManager $shippingManager, Session $session, SecurityContext $securityContext, CheckoutCustomFieldManager $customFieldManager, AffiliateManager $affiliateManager, FormFactoryInterface $formFactory)
    {
        $this->configurationManager = $configurationManager;
        $this->orderManager         = $orderManager;
        $this->cartManager             = $cartManager;
        $this->paymentManager         = $paymentManager;
        $this->shippingManager         = $shippingManager;
        $this->session                 = $session;
        $this->securityContext         = $securityContext;
        $this->customFieldManager     = $customFieldManager;
        $this->affiliateManager     = $affiliateManager;
        $this->formFactory             = $formFactory;
        
        $this->stepHandlers = new ArrayCollection();
    }

    /**
     * reset
     * 
     * Resets the checkout process
     * 
     * @param null|int $lastCompletedOrder
     * @param bool $deleteOrder - Will delete (if any) the order in the database
     */
    public function reset($lastCompletedOrder = null, $deleteOrder = false)
    {
        $this->getSession()->set(self::SESSION_TAG_CURRENT_STEP, null);
        $this->getSession()->set(self::SESSION_TAG_LAST_COMPLETED_STEP, null);
        
        $this->getOrderManager()->reset($lastCompletedOrder, $deleteOrder);
        
        return $this;
    }
    
    /**
     * getOrderManager
     *
     * @return OrderManager
     */
    public function getOrderManager()
    {
        return $this->orderManager;
    }
    
    /**
     * getAffiliateManager
     *
     * @return AffiliateManager
     */
    public function getAffiliateManager()
    {
        return $this->affiliateManager;
    }
    
    /**
     * getFormFactory
     *
     * @return FormFactoryBuilderInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }
    
    /**
     * getCustomFieldManager
     *
     * @return CheckoutCustomFieldManager
     */
    public function getCustomFieldManager()
    {
        return $this->customFieldManager;
    }
    
    /**
     * getSession
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
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
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * getPaymentManager
     *
     * @return PaymentManager
     */
    public function getPaymentManager()
    {
        return $this->paymentManager;
    }

    /**
     * getShippingManager
     *
     * @return ShippingManager
     */
    public function getShippingManager()
    {
        return $this->shippingManager;
    }

    /**
     * getPaymentMethods
     * 
     * Retrieves all registered payment providers
     * 
     * @return ArrayCollection
     */
    public function getPaymentProviders()
    {
        return $this->getPaymentManager()->getProviders();
    }

    /**
     * getPaymentMethod
     * 
     * @param  string $name
     * 
     * @return PaymentProviderInterface
     */
    public function getPaymentProvider($name)
    {
        return $this->getPaymentManager()->getProvider($name);
    }

    /**
     * getShippingProviders

     * @return ArrayCollection
     */
    public function getShippingProviders()
    {
        return $this->getShippingManager()->getProviders();
    }

    /**
     * getShippingProvider
     * 
     * @param  string $name 
     * @return ShippingProviderInterface
     */
    public function getShippingProvider($name)
    {
        return $this->getShippingManager()->getProvider($name);
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
            $type = 'notice';
        }
        
        $this->getSession()->getFlashBag()->add('checkout_'.$type, $message);
        return $this;
    }
    
    /**
     * addStepHandler
     * 
     * Add a step handler to the checkout process
     * 
     * @param CheckoutStepHandlerInterface $stepHandler
     */
    public function addStepHandler(CheckoutStepHandlerInterface $stepHandler)
    {
        
        if($this->getStepHandlers()->containsKey($stepHandler->getName())) {
            throw new \InvalidArgumentException(sprintf('Step Handler %s Already Exists',
                $stepHandler->getName()
            ));
        }
        $this->getStepHandlers()->set($stepHandler->getName(), $stepHandler);
        return $this;
    }
    
    /**
     * getStepHandler
     * 
     * Get a CheckoutStepHandlerInterface if it exists
     * 
     * @return CheckoutStepHandlerInterface
     * @throws Exception - If step handler does not exist
     */
    public function getStepHandler(CheckoutStepHandlerInterface $stepHandler)
    {
        if($this->getStepHandlers()->containsKey($stepHandler->getName())){
            return $this->stepHandlers->get($stepHandler->getName());
        }
        
        throw new \Exception(sprintf('Step handler %s does not exist', $stepHandler->getName()));
    }
    
    /**
     * getStepHandlers
     * 
     * @return Collection
     */
    public function getStepHandlers()
    {
        return $this->stepHandlers;
    }
    
    /**
     * getStepHandlersByStep
     * 
     * @return array
     */
    public function getStepHandlersByStep($step)
    {
        $return = array();
        foreach($this->getStepHandlers() as $stepHandler) {
            if($stepHandler->getStep() == $step){
                $priority = $stepHandler->getPriority();
                while(isset($return[$priority])){
                    $priority++;
                }
                $return[$priority] = $stepHandler;
            }
        }
        ksort($return); // sort by the priority
        return $return;
    }
    
    /**
     * getCheckoutForm
     *
     * @param OrderInterface $order
     *
     * @return Form
     */
    public function getCheckoutForm(OrderInterface $order = null, array $formOptions = array())
    {
        if(is_null($order) && ! $order = $this->getOrderManager()->getOrder() ){
            $order = $this->getOrderManager()->createOrder();
        }

        // update order items
        $this->getOrderManager()->updateOrderItems($order, false);
        
        // merge form options for each handler
        foreach($this->getStepHandlersByStep($this->getCurrentStep()) as $stepHandler) {
            $stepHandler->preBuildForm($order);
            $formOptions = array_merge_recursive($formOptions, $stepHandler->buildFormOptions($order));
        } 

        $checkoutForm = $this->getFormFactory()
        ->createNamedBuilder('checkout', 'form', $order, array_merge_recursive(array(
            'data_class' => get_class($order),
            'csrf_protection' => true, 
            'cascade_validation' => true,
            'validation_groups' => array('checkout_step_'.$this->getCurrentStep())
        ), $formOptions));
        
        // pass the current form builder to each step handler and let the handler
        // build on top of the form
        foreach($this->getStepHandlersByStep($this->getCurrentStep()) as $stepHandler) {
            $stepHandler->buildForm($order, $checkoutForm);
        }
        
        return $checkoutForm->getForm();
    }

    /**
     * setSessionData
     *
     * @param  string          $tag
     * @param  mixed           $data
     * @return CheckoutManager
     */
    public function setSessionData($tag, $data)
    {
        $this->getSession()->set($tag, serialize($data));
        return $this;
    }

    /**
     * getSessionData
     *
     * @param  string $tag
     * @param  mixed  $defaultValue
     * @return mixed
     */
    public function getSessionData($tag, $defaultValue = null)
    {
        return unserialize($this->getSession()->get($tag, $defaultValue));
    }

    /**
     * hasSessionData
     *
     * @param  string $tag
     * @return bool
     */
    public function hasSessionData($tag)
    {
        return $this->getSession()->has($tag);
    }

    /**
     * hasCurrentOrder
     *
     * @return bool
     */
    public function hasCurrentOrder()
    {
        return $this->getOrderManager()->hasCurrentOrder();
    }

    /**
     * getCurrentOrderId
     *
     * @return int|null
     */
    public function getCurrentOrderId()
    {
        return $this->getOrderManager()->getCurrentOrderId();
    }

    /**
     * setCurrentOrderId
     *
     * @param int $currentOrderId
     */
    public function setCurrentOrderId($currentOrderId = null)
    {
        $this->getOrderManager()->setCurrentOrderId($currentOrderId);
        return $this;
    }

    /**
     * getCurrentStep
     *
     * @return int
     */
    public function getCurrentStep()
    {
        $currentStep = $this->getSession()->get(self::SESSION_TAG_CURRENT_STEP);
        
        if (is_null($currentStep)) {
            $this->setCurrentStep(static::STARTING_STEP_POSITION);
            return static::STARTING_STEP_POSITION;
        }

        return $currentStep;
    }

    /**
     * @param  int  $currentStep
     * @return CheckoutManager
     *
     * @throws CheckoutStepOutOfRangeException
     */
    public function setCurrentStep($currentStep = 1)
    {
        /*if ($currentStep > self::STEP_COMPLETE) {
            if ($this->getLastCompletedStep() > self::STEP_COMPLETE) {
                throw new CheckoutStepOutOfRangeException("Checkout step is out of range");
            }
            $currentStep = $this->getLastCompletedStep();
        }*/
        $this->getSession()->set(self::SESSION_TAG_CURRENT_STEP, $currentStep);

        return $this;
    }

    /**
     * getLastCompletedStep
     *
     * @return int
     */
    public function getLastCompletedStep()
    {
        return $this->getSession()->get(self::SESSION_TAG_LAST_COMPLETED_STEP, 1); 
    }

    /**
     * @param  int $step
     * @return CheckoutManager
     */
    public function setLastCompletedStep($step = 1)
    {
        $this->getSession()->set(self::SESSION_TAG_LAST_COMPLETED_STEP, $step);
        return $this;
    }

    /**
     * getLastCompletedOrder
     *
     * @return int
     */
    public function getLastCompletedOrder()
    {
        return $this->getSession()->get(self::SESSION_TAG_LAST_COMPLETED_ORDER);
    }

    /**
     * setLastCompletedOrder
     *
     * @param  int $orderId
     * 
     * @return CheckoutManager
     */
    public function setLastCompletedOrder($orderId)
    {
        $this->getOrderManager()->setLastCompletedOrderId($orderId);
        return $this;
    }
    
    /**
     * getLastStep
     * 
     * Gets the last step number to know when to process the order
     * 
     * @return int
     */
    public function getLastStep()
    {
        $return = 1;
        foreach($this->getStepHandlers() as $stepHandler) {
            if($stepHandler->getStep() > $return){
                $return = $stepHandler->getStep();
            }
        }
        return $return;
    }
    
    /**
     * validateStepHandlers
     * 
     * Validates the currently set CheckoutStepHandlerInterface's to make
     * sure they meet the requirements to complete a successful checkout
     * 
     * @throws RuntimeException
     */
    public function validateStepHandlers()
    {
        $lastStep = $this->getLastStep();
        for($i = 1; $i < $lastStep; $i++) {
            if(!count($this->getStepHandlersByStep($i))){
                throw \RuntimeException('Checkout Step Handlers are in a non sequential order. 
                        Make sure each step from step 1 to the last registered step exists.');
            }
        }
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
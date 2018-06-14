<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout\CheckoutStep;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Model\OrderInterface;
use Symfony\Component\Validator\Constraints;
use Spliced\Component\Commerce\Form\Type\CheckoutRegistrationFormType;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spliced\Component\Commerce\Form\Type\CheckoutShipmentFormType;
use Spliced\Component\Commerce\Form\Type\CheckoutPaymentFormType;
use Doctrine\ORM\NoResultException;

/**
 * OnePageCheckoutStepHandler
 * 
 * This CheckoutStepHandler handles all checkout processes
 * in one handler. Ideally, you should not register any other step handlers
 * other than a StepHandler which reviews the order for the customer.
 * 
 * So in other words, when using this step handler, use it in conjuction
 * with ReviewCheckoutStepHandler, which creates a two step checkout process,
 * one to collect information and one to review that information at which point
 * the customer will submit and the order will be processed.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class OnePageCheckoutStepHandler extends CheckoutStepHandler
{    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param CheckoutManager $checkoutManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param SecurityContext $securityContext
     * @param EngineInterface $templatingEngine
     */
    public function __construct(ConfigurationManager $configurationManager, CheckoutManager $checkoutManager, SecurityContext $securityContext, EventDispatcherInterface $eventDispatcher, EngineInterface $templatingEngine)
    {
        $this->configurationManager = $configurationManager;
        $this->checkoutManager = $checkoutManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->securityContext = $securityContext;
        $this->templatingEngine = $templatingEngine;
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
     * getEventDispatcher
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
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
     * getSecurityContext
     *
     * @return SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * getTemplatingEngine
     *
     * @return SecurityContext
     */
    protected function getTemplatingEngine()
    {
        return $this->templatingEngine;
    }
     
    /**
     * @var $position - Default position for this step
     */
    protected $position = 1;
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'onepage';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProgressBarLabel()
    {
        return 'Checkout Details';
    }
    
    /**
     * {@inheritDoc}
     */
    public function preBuildForm(OrderInterface $order)
    {

    }


    /**
     * {@inheritDoc}
     */
    public function buildFormOptions(OrderInterface $order, array $formOptions = array())
    {
        return array('validation_groups' => array(
            $this->getName(),
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(OrderInterface $order, FormBuilderInterface $builder)
    {     
        $builder->add('billingFirstName', 'text', array(
            'label' => 'First Name',
            'constraints' => array(
                new Constraints\NotBlank()
            )
        ))
        ->add('billingLastName', 'text', array(
            'label' => 'Last Name',
            'constraints' => array(
                new Constraints\NotBlank()
            )
        ))->add('billingCompany', 'text', array(
            'label' => 'Company',
        	'required' => false,
            'constraints' => array(
            )
        ));        
         
        if(!$this->getSecurityContext()->isGranted('ROLE_USER')){
            $builder->add('email', 'email', array(
            'required' => true,
            'error_bubbling' => false,
            'label' => 'E-Mail',
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Email()
            )))
            ->add('customer', new CheckoutRegistrationFormType());
        }
        
        $builder
        ->add('billingAddress', 'text', array(
            'label' => 'Address',
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ))
        ->add('billingAddress2', 'text', array('label' => '')) 
        ->add('billingCity', 'text', array(
            'label' => 'City',
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ))
        ->add('billingState', 'text', array(
            'label' => 'State',
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ))
        ->add('billingZipcode', 'text', array(
            'label' => 'Zipcode',
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ))
        ->add('billingCountry', 'country', array(
            'empty_value' => 'Select Your Country',
            'preferred_choices' => array('US','CA','GB'),
            'label' => 'Country',
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ))
        ->add('billingPhoneNumber', 'text', array(
            'required' => false,
            'label' => 'Phone Number',
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ))
        ->add('shippingFirstName', 'text', array(
            'label' => 'First Name',
        ))
        ->add('shippingLastName', 'text', array(
            'label' => 'Last Name',
        ))
        ->add('shippingCompany', 'text', array(
        	'label' => 'Company',
        	'required' => false,
        ))
        ->add('shippingAddress', 'text', array(
            'label' => 'Address',
        ))
        ->add('shippingAddress2', 'textarea', array(
            'label' => '',
        ))
        ->add('shippingCity', 'text', array(
            'label' => 'City',
        ))
        ->add('shippingState', 'text', array(
            'label' => 'State',
        ))
        ->add('shippingZipcode', 'text', array(
            'label' => 'Zipcode',
        ))
        ->add('shippingCountry', 'country', array(
            'empty_value' => 'Select Your Country',
            'preferred_choices' => array('US','CA','GB'),
            'label' => 'Country',
        ))
        ->add('shippingPhoneNumber', 'text', array('required' => false, 'label' => 'Phone Number'));
        
        if($this->getSecurityContext()->isGranted('ROLE_USER')) {
            $builder->add('saveBillingAddress', 'checkbox', array('required' => false, 'value' => 1))
            ->add('saveShippingAddress', 'checkbox', array('required' => false, 'value' => 1));
        }
        
        $builder->add('shipment', new CheckoutShipmentFormType($order, $this->getCheckoutManager()));
        $builder->add('payment', new CheckoutPaymentFormType($order, $this->getCheckoutManager()));

    }
    
    /**
     * {@inheritDoc}
     */
    public function process(FormInterface $form, Request $request)
    {
        if($request->getMethod() == 'POST') {
        	
        	if($request->request->get('action') == 'update-shipping') {
        		$form->bind($request);
				
        		$order = $form->getData();
        		
        		$newForm = $this->getCheckoutManager()->getCheckoutForm($order);
        		        		        		
		        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
		            'form' => $newForm->createView(),
		            'step' => $this->getCheckoutManager()->getCurrentStep(),
		            'step_template' => 'onepage', 
		        ));
        	}
        	
            if($form->bind($request) && $form->isValid()) {
                $order = $form->getData();
                $shipment = $order->getShipment();
                $payment = $order->getPayment();
                $creditCard = $payment->getCreditCard();
                
                // handle a new customer registration
                if($order->getCustomer() && !$order->getCustomer()->getId() && !$order->getCustomer()->getPlainPassword()) {
                    $order->setCustomer(null);
                } else if($order->getCustomer() && !$order->getCustomer()->getId()) {
                
                    $order->getCustomer()->setEmail($order->getEmail());
                
                    try{
                        $existingCustomer = $this->getConfigurationManager()
                        ->getEntityManager()
                        ->getRepository($this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER))
                        ->findOneByEmail($order->getCustomer()->getEmail());
                         
                        if(!$existingCustomer) {
                            throw new NoResultException('Customer Does Not Exists');
                        }
                         
                        $this->getCheckoutManager()->addFlash('error', 'E-Mail is already registered');
                
                        /*if($request->isXmlHttpRequest()) {
                            return new JsonResponse(array(
                                'success' => true,
                                'replace_many' => array(
                                    '#checkout-content' => $this->getTemplatingEngine()->render('SplicedCommerceBundle:Checkout:index_content.html.twig',array(
                                        'form' => $form->createView(),
                                        'step' => $this->getCheckoutManager()->getCurrentStep(),
                                    ))
                           )));
                        }*/
                        
                        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
                            'form' => $form->createView(),
                            'step' => $this->getCheckoutManager()->getCurrentStep(),
                            'step_template' => 'onepage',
                        ));
                        
                    } catch(NoResultException $e) {
                        // save the customer
                        $this->getEventDispatcher()->dispatch(
                            Events\SecurityEvent::EVENT_SECURITY_REGISTRATION_COMPLETE,
                            new Events\RegistrationCompleteEvent($order->getCustomer())
                        );
                
                        $this->getCheckoutManager()->addFlash('success', 'Your account has been created successfully');
                    }
                }
                
                // fill shipping address with billing if none was provided
                if (!$order->hasAlternateShippingAddress()) {
                    // set the shipping address as the billing if
                    // we do not have an alternate address provided
                    $order->setShippingName($order->getBillingFullName());
                    $order->setShippingAddress($order->getBillingAddress());
                    $order->setShippingAddress2($order->getBillingAddress2());
                    $order->setShippingCity($order->getBillingCity());
                    $order->setShippingState($order->getBillingState());
                    $order->setShippingZipcode($order->getBillingZipcode());
                    $order->setShippingCountry($order->getBillingCountry());
                    $order->setShippingPhoneNumber($order->getBillingPhoneNumber());
                }
                
                // handle shipment
                if($shipment->getUserSelection()){
                        
                    $shippingMethod = $this->getCheckoutManager()
                    ->getShippingManager()->getMethodByFullName($shipment->getUserSelection());
                        
                    $shippingProvider = $shippingMethod->getProvider();
                        
                    $shipment->setShipmentProvider($shippingProvider->getName())
                    ->setShipmentMethod($shippingMethod->getName());
                        
                } else if($shipment->getShipmentProvider() && $shipment->getShipmentMethod()){
                    $shippingProvider = $this->getCheckoutManager()
                    ->getShippingProvider($shipment->getShipmentProvider());
                    $shippingMethod = $shippingProvider->getMethod($shipment->getShipmentMethod());
                        
                    $shipment->setShipmentProvider($shippingProvider->getName())
                    ->setShipmentMethod($shippingMethod->getName());
                } else {
                    die('error! no method selected! handle!!');
                }
                
                $shipment->setShipmentCost($shippingMethod->getPrice());
                $shipment->setShipmentPaid($shippingMethod->getPrice());
                
                // handle payent
                if ($creditCard instanceof CustomerCreditCardInterface) {
                    if (!$creditCard->getId()) {
                        $creditCard->setPayment($payment);
                    }
                     
                    if ($this->getSecurityContext()->isGranted('ROLE_USER')) {
                        $creditCard->setCustomer($this->getSecurityContext()->getToken()->getUser());
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
                }                
                
                return new Events\CheckoutMoveStepEvent(
                    $order, 
                    $this->getCheckoutManager()->getCurrentStep()
                );
            }// end valid form
        } 
        
        /*if($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
                'replace_many' => array(
                    '#checkout-content' => $this->getTemplatingEngine()->render('SplicedCommerceBundle:Checkout:index_content.html.twig',array(
                        'form' => $form->createView(),
                        'step' => $this->getCheckoutManager()->getCurrentStep(),  
                        'step_template' => 'onepage',
                    ))
                )
            ));
        }
        */
        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
            'form' => $form->createView(),
            'step' => $this->getCheckoutManager()->getCurrentStep(),
            'step_template' => 'onepage', 
        ));
    }
}
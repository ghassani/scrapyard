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
use Doctrine\ORM\NoResultException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * LoginRegisterCheckoutStepHandler
 * 
 * This CheckoutStepHandler handles either a customer checking out
 * as guest, register new account, or login to existing account.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LoginRegisterCheckoutStepHandler extends CheckoutStepHandler
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
        return 'login_register';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProgressBarLabel()
    {
        return 'Account';
    }
    
    /**
     * {@inheritDoc}
     */
    public function preBuildForm(OrderInterface $order)
    {
        if($this->getSecurityContext()->isGranted('ROLE_USER')){
            // we set the next step because we do not need to collect any
            // user information on this step as the user is already logged in
            $this->getCheckoutManager()->setLastCompletedStep($this->getStep());
            $this->getCheckoutManager()->setCurrentStep($this->getStep() + 1);
        }
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
        ))
        ->add('email', 'email', array(
            'required' => true,
            'error_bubbling' => false,
            'label' => 'E-Mail',
              'constraints' => array(
                  new Constraints\NotBlank(),
                  new Constraints\Email()
            )
        )); 
        
        if(!$this->getSecurityContext()->isGranted('ROLE_USER')){
            $builder->add('customer', new CheckoutRegistrationFormType());
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function process(FormInterface $form, Request $request)
    {
        if($request->getMethod() == 'POST') {
            if($form->bind($request) && $form->isValid()) {
                $order = $form->getData();
                
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
                
                        if($request->isXmlHttpRequest()) {
                            return new JsonResponse(array(
                                'success' => true,
                                'replace_many' => array(
                                    '#checkout-content' => $this->getTemplatingEngine()->render('SplicedCommerceBundle:Checkout:index_content.html.twig',array(
                                        'form' => $form->createView(),
                                        'step' => $this->getCheckoutManager()->getCurrentStep(),
                                     ))
                            )));
                        }
                         
                        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
                            'form' => $form->createView(),
                            'step' => $this->getCheckoutManager()->getCurrentStep(),
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
                
                return new Events\CheckoutMoveStepEvent(
                    $order, 
                    $this->getCheckoutManager()->getCurrentStep()
                );
            }
        }
                
        if($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
                'replace_many' => array(
                    '#checkout-content' => $this->getTemplatingEngine()->render('SplicedCommerceBundle:Checkout:index_content.html.twig',array(
                        'form' => $form->createView(),
                        'step' => $this->getCheckoutManager()->getCurrentStep(),   
                        'step_template' => 'login_register',
                    ))
                )
            ));
        }
        
        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
            'form' => $form->createView(),
            'step' => $this->getCheckoutManager()->getCurrentStep(),
            'step_template' => 'login_register',
        ));
    }
}
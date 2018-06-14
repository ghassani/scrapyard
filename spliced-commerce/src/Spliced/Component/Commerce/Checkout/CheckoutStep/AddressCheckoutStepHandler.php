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
use Spliced\Component\Commerce\Event\CheckoutMoveStepEvent;
use Spliced\Component\Commerce\Model\OrderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * AddressCheckoutStepHandler
 * 
 * This CheckoutStepHandler handles collecting a customers
 * billing and shipping address
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AddressCheckoutStepHandler extends CheckoutStepHandler
{    
    /**
     * @var $position - Default position for this step
     */
    protected $position = 2;

    /**
     * Constructor
     * 
     * @param CheckoutManager $checkoutManager
     * @param SecurityContext $securityContext
     * @param EngineInterface $templatingEngine
     */
    public function __construct(CheckoutManager $checkoutManager, SecurityContext $securityContext, EngineInterface $templatingEngine)
    {
        $this->checkoutManager = $checkoutManager;
        $this->securityContext = $securityContext;
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * getCheckoutManager
     *
     * @return CheckoutManager
     */
    public function getCheckoutManager()
    {
        return $this->checkoutManager;
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
     * getTemplatingEngine
     *
     * @return SecurityContext
     */
    public function getTemplatingEngine()
    {
        return $this->templatingEngine;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'address';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProgressBarLabel()
    {
        return 'Address';
    }

    /**
     * {@inheritDoc}
     */
    public function buildFormOptions(OrderInterface $order, array $formOptions = array())
    {
        return array(
            'validation_groups' => array(
                $this->getName(),
            ),
            'constraints' => array(
                //new Constraints\Callback(array(array($this, 'validateBillingAddress'))),
            )
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(OrderInterface $order, FormBuilderInterface $builder)
    {
         $builder
            ->add('billingFirstName', 'text', array(
                'label' => 'First Name',
                'constraints' => array(
                    new Constraints\NotBlank(),
                )
            ))
            ->add('billingLastName', 'text', array(
                'label' => 'Last Name',
                'constraints' => array(
                    new Constraints\NotBlank(),
                )
            ))
            ->add('billingCompany', 'text', array(
            	'label' => 'Company',
            	'constraints' => array(
            		new Constraints\NotBlank(),
            	)
            ))
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
                'data' => 'US',
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
            ->add('shippingFirstName')
            ->add('shippingLastName')
            ->add('shippingCompany')
            ->add('shippingAddress')
            ->add('shippingAddress2', 'textarea')
            ->add('shippingCity')
            ->add('shippingState')
            ->add('shippingZipcode')
            ->add('shippingCountry', 'country', array(
                'empty_value' => 'Select Your Country',
                'preferred_choices' => array('US','CA','GB'),
            ))
            ->add('shippingPhoneNumber', 'text', array('required' => false));
            
            if($this->getSecurityContext()->isGranted('ROLE_USER')) {
                $builder->add('saveBillingAddress', 'checkbox', array('required' => false, 'value' => 1))
                ->add('saveShippingAddress', 'checkbox', array('required' => false, 'value' => 1));
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
                
                if (!$order->hasAlternateShippingAddress()) {
                    // set the shipping address as the billing if 
                    // we do not have an alternate address provided
                	$order->setShippingFirstName($order->getBillingFirstName());
                	$order->setShippingLastName($order->getBillingLastName());
                	$order->setShippingCompany($order->getBillingCompany());
                    $order->setShippingAddress($order->getBillingAddress());
                    $order->setShippingAddress2($order->getBillingAddress2());
                    $order->setShippingCity($order->getBillingCity());
                    $order->setShippingState($order->getBillingState());
                    $order->setShippingZipcode($order->getBillingZipcode());
                    $order->setShippingCountry($order->getBillingCountry());
                    $order->setShippingPhoneNumber($order->getBillingPhoneNumber());
                }

                return new CheckoutMoveStepEvent(
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
                    'step_template' => 'address',
                ))
            )));
        }
         
        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
            'form' => $form->createView(),
            'step' => $this->getCheckoutManager()->getCurrentStep(),
            'step_template' => 'address',
        ));
    }
}
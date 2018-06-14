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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Spliced\Component\Commerce\Event\CheckoutMoveStepEvent;
use Spliced\Component\Commerce\Form\Type\CheckoutPaymentFormType;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;
use Spliced\Component\Commerce\Model\CustomerCreditCardInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * PaymentCheckoutStepHandler
 * 
 * This CheckoutStepHandler handles collecting payment method
 * and required payment fields for the checkout process
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PaymentCheckoutStepHandler extends CheckoutStepHandler
{    
    /**
     * @var $position - Default position for this step
     */
    protected $position = 4;

    /**
     * Constructor
     * 
     * @param CheckoutManager $checkoutManager
     * @param EngineInterface $templatingEngine
     */
    public function __construct(CheckoutManager $checkoutManager, EncryptionManager $encryptionManager, SecurityContext $securityContext, EngineInterface $templatingEngine)
    {
        $this->checkoutManager = $checkoutManager;
        $this->encryptionManager = $encryptionManager;
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
     * getTemplatingEngine
     *
     * @return SecurityContext
     */
    public function getTemplatingEngine()
    {
        return $this->templatingEngine;
    }

    /**
     * getEncryptionManager
     *
     * @return EncryptionManager
     */
    public function getEncryptionManager()
    {
        return $this->encryptionManager;
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
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'payment';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProgressBarLabel()
    {
        return 'Payment';
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
        $builder->add('payment', new CheckoutPaymentFormType($order, $this->getCheckoutManager()));
        
    }
    

    /**
     * {@inheritDoc}
     */
    public function process(FormInterface $form, Request $request)
    {
        $em = $this->getCheckoutManager()->getConfigurationManager()->getEntityManager();

        if($request->getMethod() == 'POST') {
            if($form->bind($request) && $form->isValid()) {
                $order = $form->getData();
                $payment = $order->getPayment();
                $creditCard = $payment->getCreditCard();
                
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
                
                $paymentMethod = $this->getCheckoutManager()->getPaymentProvider($payment->getPaymentMethod());

                $payment->setMethodName($paymentMethod->getLabel().' '.$paymentMethod->getLabel2());
                
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
                    'step_template' => 'payment',
                ))
            )));
        }
         
        return $this->getTemplatingEngine()->renderResponse('SplicedCommerceBundle:Checkout:index.html.twig', array(
            'form' => $form->createView(),
            'step' => $this->getCheckoutManager()->getCurrentStep(),
            'step_template' => 'payment',
        ));
    }
}
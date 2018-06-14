<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Payment\PaymentProviderCollection;

/**
 * CheckoutPaymentFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutPaymentFormType extends AbstractType
{

    /**
     * Constructor
     * 
     * @param Entity\Order $order
     * @param CheckoutManager $checkoutManager
     */
    public function __construct(OrderInterface $order, CheckoutManager $checkoutManager)
    {
        $this->checkoutManager = $checkoutManager;
        $this->order = $order;
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
      * getOrder
      * 
      * @return OrderInterface
      */
      protected function getOrder()
      {
          return $this->order;
      }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $paymentMethods = $this->getCheckoutManager()->getPaymentManager()->getProviders();
        $creditCardMethods = $this->getCheckoutManager()->getPaymentManager()->getCreditCardProviders();
        
        if($this->getOrder()->getPayment() && $this->getOrder()->getPayment()->getPaymentMethod()){
            $selectedMethod = $this->getOrder()->getPayment()->getPaymentMethod();
        }
        
        if(!isset($selectedMethod) && $paymentMethods->count()){
            $selectedMethod = $paymentMethods->first()->getName();
        }
                
        $builder->add('paymentMethod','choice', array(
            'choices' => $this->getPaymentMethods()->toArray(),
            'required' => true,
            'expanded' => true,
            'data' => $selectedMethod,
        ));
        
    
        foreach($creditCardMethods as $method){
            if($creditCardMethods->count() == 1){
                $builder->add('creditCard', new CheckoutCreditCardFormType($this->getOrder(), $this->getCheckoutManager()));
            } else {
                $builder->add(
                    sprintf('%s_creditCard',$method->getName()), 
                    new CheckoutCreditCardFormType($this->getOrder(), $this->getCheckoutManager(), $method->getName())
                );
            }
        }
    }

 
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\OrderPayment',
            'cascade_validation' => true,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'checkout_payment';
    }
    
    /**
     * getPaymentMethods
     * 
     * @return PaymentProviderCollection
     */
     protected function getPaymentMethods()
     {
         $return = new PaymentProviderCollection();
        foreach($this->getCheckoutManager()->getPaymentManager()->getProviders() as $provider){
            $return->set($provider->getName(), $provider->getLabel());
        }
        return $return;
     }

}

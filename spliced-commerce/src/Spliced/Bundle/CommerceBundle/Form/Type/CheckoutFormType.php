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
use Spliced\Bundle\CommerceBundle\Entity;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * CheckoutFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutFormType extends AbstractType
{

    /* @var int $step - The current checkout step */
    protected $step;

    /* @var bool $isLoggedIn */
    protected $isLoggedIn = false;

    /**
     * @param OrderInterface  $order
     * @param CheckoutManager $checkoutManager
     * @param int             $step
     */
    public function __construct(OrderInterface $order, CheckoutManager $checkoutManager, $step = 1)
    {
        $this->checkoutManager = $checkoutManager;
        $this->step = $step;
        $this->order = $order;
        $this->isLoggedIn = $this->getCheckoutManager()->getSecurityContext()->isGranted('ROLE_USER');
        
        $this->customFields = $this->getCheckoutManager()
        ->getCustomFieldManager()
        ->getFieldsByStep($this->getStep());
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
     * getStep
     * 
     * @return int
     */
    protected function getStep()
    {
        return $this->step;
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
     * isLoggedIn
     *
     * @return bool
     */
    protected function isLoggedIn()
    {
        return $this->isLoggedIn === true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $customer = $this->getCheckoutManager()->getSecurityContext()
          ->getToken()
          ->getUser();

        if ($this->getStep() == CheckoutManager::STEP_ACCOUNT && !$this->isLoggedIn()) {
            
            $builder->add('billingFirstName', 'text', array('label' => 'First Name'))
              ->add('billingLastName', 'text', array('label' => 'Last Name'))
              ->add('email', 'email', array(
                'required' => true,
                'error_bubbling' => false,
                'label' => 'E-Mail'
            ));
        }

        if ($this->getStep() == CheckoutManager::STEP_ADDRESS) {
            $builder
            ->add('billingFirstName', 'text', array('label' => 'First Name'))
            ->add('billingLastName', 'text', array('label' => 'Last Name'))
            ->add('billingAddress', 'text', array('label' => 'Address'))
            ->add('billingAddress2', 'text', array('label' => ''))
            ->add('billingCity', 'text', array('label' => 'City'))
            ->add('billingState', 'text', array('label' => 'State'))
            ->add('billingZipcode', 'text', array('label' => 'Zipcode'))
            ->add('billingCountry', 'country', array(
                'empty_value' => 'Select Your Country',
                'preferred_choices' => array('US','CA','GB'),
                'data' => 'US',
                'label' => 'Country',
            ))
            ->add('billingPhoneNumber', 'text', array('required' => false, 'label' => 'Phone Number'))
            ->add('shippingName')
            ->add('shippingAttn')
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
            
            if($this->isLoggedIn()) {
                $builder->add('saveBillingAddress', 'checkbox', array('required' => false, 'value' => 1))
                ->add('saveShippingAddress', 'checkbox', array('required' => false, 'value' => 1));
            }
        }

        if ($this->getStep() == CheckoutManager::STEP_SHIPPING) {
            $builder->add('shipment', new CheckoutShipmentFormType($this->order, $this->checkoutManager));
        }

        if ($this->getStep() == CheckoutManager::STEP_PAYMENT) {
            $builder->add('payment', new CheckoutPaymentFormType($this->order, $this->checkoutManager));
        }
        
                
        if(count($this->customFields)){
            $builder->add(
                'customFieldValues', 
                new CheckoutCustomFieldsFormType($this->getOrder(), $this->customFields),
                array()
            );
        }
    }


    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\Order',
            'validation_groups' => $this->getValidationGroups(),
            'cascade_validation' => true,
        ));
    }
    
    /**
     * {@inheridDoc}
     */
    public function getName()
    {
        return 'checkout';
    }
    
    /**
     * getValidationGroups
     *
     * @return array
     */
    private function getValidationGroups()
    {
        $groups = array('checkout',sprintf('checkout_step_%s',$this->getStep()));
        
        if (!$this->isLoggedIn()) {
            $groups[] = 'checkout_guest';
        }
        
        if(count($this->customFields)){
            $groups[] = 'checkout_custom_fields';
        }
        
        return $groups;
    }



}

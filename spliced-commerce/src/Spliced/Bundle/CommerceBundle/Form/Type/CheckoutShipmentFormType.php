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
use Spliced\Component\Commerce\Shipping\ShippingMethodCollection;

/**
 * CheckoutShipmentFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutShipmentFormType extends AbstractType
{

    /**
     *
     */
    public function __construct(Entity\Order $order, CheckoutManager $checkoutManager)
    {
        $this->checkoutManager = $checkoutManager;
        $this->order = $order;
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
     * getCheckoutManager
     * 
     * @return CheckoutManager 
     */
    protected function getCheckoutManager()
    {
        return $this->checkoutManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $shippingMethods = $this->getShippingChoices();

        if($this->getOrder()->getShipment() && $this->getOrder()->getShipment()->getShipmentProvider() && $this->getOrder()->getShipment()->getShipmentMethod()){
            $selectedMethod = strtolower(sprintf('%s_%s',
                $this->getOrder()->getShipment()->getShipmentProvider(),
                $this->getOrder()->getShipment()->getShipmentMethod()
            ));
        }
        
        if(!isset($selectedMethod) && $shippingMethods->count()){
            $selectedMethod = $shippingMethods->first()->getFullName();
        }
        
        $builder->add('userSelection', 'choice', array(
            'choices' => $shippingMethods->toArray(),
            'required' => true,
            'data' => $selectedMethod,
            'expanded' => true,
            'error_bubbling' => false,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\OrderShipment',
            'cascade_validation' => true,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'checkout_shipment';
    }

    
    /**
     * getShippingChoices
     */
     public function getShippingChoices()
     {
         $return = new ShippingMethodCollection();
        if($this->getOrder()->getDestinationCountry()){
            foreach($this->getCheckoutManager()->getShippingManager()->getAvailableMethodsForDesination($this->getOrder()->getDestinationCountry()) as $method) {
                $return->set($method->getFullName(), $method);
            }
        }
        return $return;
     }
}

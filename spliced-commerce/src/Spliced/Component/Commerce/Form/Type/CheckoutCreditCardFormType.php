<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Checkout\CheckoutManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CheckoutCreditCardFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutCreditCardFormType extends CreditCardFormType
{


    /**
     * Constructor
     * 
     * @param Entity\Order $order
     * @param CheckoutManager $checkoutManager
     */
    public function __construct(OrderInterface $order, CheckoutManager $checkoutManager, $prependName = null)
    {
        $this->checkoutManager = $checkoutManager;
        $this->order = $order;
        $this->prependName = $prependName;
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
        parent::buildForm($builder,$options);

    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\CustomerCreditCard',
            'validation_groups' => $this->getValidationGroups(),
            'cascade_validation' => true,
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function getValidationGroups()
    {
        $groups = array();
        if ($this->order->getPayment() && $this->order->getPayment()->getPaymentMethod()) {
            $method = $this->checkoutManager->getPaymentProvider($this->order->getPayment()->getPaymentMethod());
            if ($method->acceptsCreditCards()) {
                $groups[] = 'validate_credit_card';
            }
        }

        return $groups;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return isset($this->prependName) && !is_null($this->prependName) ? $this->prependName.'_credit_card'  : 'credit_card';
    }
}

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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * GuestOrderLookupFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GuestOrderLookupFormType extends AbstractType
{
    const LOOKUP_TYPE_ORDER_NUMBER_EMAIL     = 1;
    const LOOKUP_TYPE_EMAIL_BILLING_ZIPCODE  = 2;
    
    /**
     * Constructor
     * 
     * @param int $lookupType
     */
    public function __construct($lookupType = self::LOOKUP_TYPE_ORDER_NUMBER_EMAIL){
        $this->lookupType = $lookupType;
    }
    
    /**
     * getLookupType
     * 
     * @return int
     */
    protected function getLookupType()
    {
        return $this->lookupType;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        switch($this->getLookupType()){
            default:
            case static::LOOKUP_TYPE_ORDER_NUMBER_EMAIL:
                $builder->add('orderNumber', 'text', array('required' => true))
                ->add('email', 'email', array('required' => true));
                break;
            case static::LOOKUP_TYPE_EMAIL_BILLING_ZIPCODE;
                $builder->add('email', 'email', array('required' => true))
                  ->add('billingZipcode', 'text', array('required' => true));
                break;
        }
        
 
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_guest_order_lookup_'.$this->getLookupType();
    }
}

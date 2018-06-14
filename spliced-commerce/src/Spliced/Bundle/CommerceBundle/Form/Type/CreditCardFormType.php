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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * CreditCardFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CreditCardFormType extends AbstractType
{


    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardNumber',  'text')
            ->add('cardExpirationMonth',  'choice', array('choices' => $this->getMonthChoices(), 'empty_value' => '',))
            ->add('cardExpirationYear', 'choice', array('choices' => $this->getYearChoices(), 'empty_value' => '',))
            ->add('cardCvv',  'text');
    }
  
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\CustomerCreditCard',
            'cascade_validation' => true,
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'credit_card';
    }
    
    /**
     * getMonthChoices
     * 
     * @return array
     */
     protected function getMonthChoices()
     {
         $return = array();
        for($i=1; $i <= 12; $i++){
            $padded = str_pad($i, 2, '0', STR_PAD_LEFT);
            $return[$padded] = $padded;
        }
        return $return;
     }
     
    
    /**
     * getYearChoices
     * 
     * @return array
     */
     protected function getYearChoices()
     {
         $return = array();
        for($i=date('Y'); $i <= (date('Y')+8); $i++){
            $return[$i] = $i;
        }
        return $return;
     }
}

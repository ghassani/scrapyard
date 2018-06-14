<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;

/**
 * ExpirationDateType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ExpirationDateType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Overwrite required option for child fields
        $options['first_options']['required']  = $options['required'];
        $options['second_options']['required'] = $options['required'];

        if (!isset($options['options']['error_bubbling'])) {
            $options['options']['error_bubbling'] = $options['error_bubbling'];
        }

        $builder
            ->addViewTransformer(new ValueToDuplicatesTransformer(array(
                $options['first_name'],
                $options['second_name'],
            )))
            ->add($options['first_name'], $options['type'], array_merge($options['options'], $options['first_options']))
            ->add($options['second_name'], $options['type'], array_merge($options['options'], $options['second_options']))
        ;
    }
        
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type'           => 'choice',
            'options'        => array(),
            'first_options'  => array('choices' => $this->getMonthChoices()),
            'second_options' => array('choices' => $this->getYearChoices()),
            'first_name'     => 'month',
            'second_name'    => 'year',
            'error_bubbling' => false,
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'repeated';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'expiration_date';
    }
    
    /**
     * getMonthChoices
     * 
     * @return array
     */
     protected function getMonthChoices()
     {
         return array(
             1 => 'January',
             2 => 'February',
             3 => 'March',
             4 => 'April',
             5 => 'May',
             6 => 'June',
             7 => 'July',
             8 => 'August',
             9 => 'September',
             10 => 'October',
             11 => 'November',
             12 => 'December',
        );
     }
     
    
    /**
     * getYearChoices
     * 
     * @return array
     */
     protected function getYearChoices($startingYear = null)
     {
         $return = array();
        $currentYear = is_null($startingYear) ? date('Y') : (int) $startingYear;
        
        for($i = $currentYear; $i <= $currentYear+15; $i++){
            $return[$i] = $i;
        }
        return $return;
     }
}

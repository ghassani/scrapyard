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

/**
 * WeightType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class WeightType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add($options['weight_name'], 'number', $options['weight_options'])
          ->add($options['unit_name'], 'choice', $options['unit_options']);
    
    }
        
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'options'        => array('required' => false),
            'weight_options'  => array('precision' => 4, 'required' => false),
            'unit_options' => array(
                'required' => false,
                'empty_value' => '-Units-', 
                'choices' => $this->getWeightMeasurementTypes()
            ),
            'weight_name'     => 'weight',
            'unit_name'    => 'unit',
            'error_bubbling' => false,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'weight';
    }
    
    /**
     * getWeightMeasurementTypes
     * 
     * @return array
     */
     protected function getWeightMeasurementTypes()
     {
         return array(
             'lb' => 'Pounds',
             'oz' => 'Ounces',
        );
     }
}
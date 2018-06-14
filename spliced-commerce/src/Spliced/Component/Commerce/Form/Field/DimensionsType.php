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
use Spliced\Component\Commerce\Form\DataTransformer\DimensionsModelTransformer;

/**
 * DimensionsType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class DimensionsType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
          ->add($options['length_name'], 'number', $options['length_options'])
          ->add($options['width_name'], 'number', $options['width_options'])
          ->add($options['height_name'], 'number', $options['height_options'])
    	  ->addModelTransformer(new DimensionsModelTransformer());
    } 
        
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'options'         => array('required' => false, 'precision' => 4),
            'length_options'  => array('required' => false, 'precision' => 4),
            'width_options'   => array('required' => false, 'precision' => 4),
            'height_options'  => array('required' => false, 'precision' => 4),
            'length_name'     => 'length',
            'width_name'      => 'width',
            'height_name'     => 'height',
            'error_bubbling'  => false,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'dimensions';
    }
}
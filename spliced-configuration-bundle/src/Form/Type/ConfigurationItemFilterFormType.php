<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigurationItemFilterFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'number', array(
                'required' => false,
                'label' => 'configuration_item_filter_form.id_label',
                'translation_domain' => 'SplicedConfigurationBundle',
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'configuration_filter';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\ConfigurationBundle\Model\ListFilter',
        ));
    }
}
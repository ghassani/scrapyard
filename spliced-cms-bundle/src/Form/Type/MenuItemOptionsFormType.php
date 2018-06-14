<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemOptionsFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('linkAttributes', 'collection', array(
            'type'         => new KeyValueFormType(),
            'allow_add'    => true,
            'label' => 'menu_form.item_options_link_attributes_label',
            'translation_domain' => 'SplicedCmsBundle',
        ))->add('childAttributes', 'collection', array(
            'type'         => new KeyValueFormType(),
            'allow_add'    => true,
            'label' => 'menu_form.item_options_child_attributes_label',
            'translation_domain' => 'SplicedCmsBundle',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'menu_item_options';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
} 
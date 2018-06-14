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

class ContentPageFilterFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'number', array(
                'required' => false,
                'label' => 'content_page_filter_form.id_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('key', 'text', array(
                'required' => false,
                'label' => 'content_page_filter_form.key_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('name', 'number', array(
                'required' => false,
                'label' => 'content_page_filter_form.name_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('createdAt', 'date', array(
                'required' => false,
                'label' => 'content_page_filter_form.created_at_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('updatedAt', 'date', array(
                'required' => false,
                'label' => 'content_page_filter_form.updated_at_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('isActive', 'choice', array(
                'empty_value' => '',
                'required' => false,
                'choices' => array(0 => 'No', 1 => 'Yes'),
                'label' => 'content_page_filter_form.is_active_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('hasDraft', 'choice', array(
                'empty_value' => '',
                'required' => false,
                'choices' => array(0 => 'No', 1 => 'Yes'),
                'label' => 'content_page_filter_form.has_draft_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'content_page_filter';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Model\ListFilter',
        ));
    }
}
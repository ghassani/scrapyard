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

class ContentPageMetaFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('metaKey', 'text', array(
                'label' => 'content_page_meta_form.meta_key_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('metaValue', 'text', array(
                'label' => 'content_page_meta_form.meta_value_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
        ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'content_page_meta';
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\ContentPageMeta',
        ));
    }
}
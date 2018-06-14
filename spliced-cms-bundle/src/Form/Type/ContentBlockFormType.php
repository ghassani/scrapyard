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
use Spliced\Bundle\CmsBundle\Entity\ContentBlock;
use Doctrine\ORM\EntityRepository;

class ContentBlockFormType extends AbstractType
{
    private $contentBlock;

    /**
     *
     */
    public function __construct(ContentBlock $contentBlock = null)
    {
        $this->contentBlock = $contentBlock;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blockKey', 'text', array(
                'label' => 'content_block_form.block_key_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('name', 'text', array(
                'label' => 'content_block_form.name_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('template', new TemplateFormType($this->contentBlock->getTemplate()), array(
                'label' => 'content_block_form.template_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))->add('isActive', 'checkbox', array(
                'label' => 'content_page_form.is_active_label',
                'translation_domain' => 'SplicedCmsBundle',
                'value' => 1,
                'required' => false,
            ));
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'content_block';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\ContentBlock',
        ));
    }
}
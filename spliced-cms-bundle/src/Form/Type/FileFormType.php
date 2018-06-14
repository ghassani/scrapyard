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
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;
use Symfony\Component\Finder\SplFileInfo;

class FileFormType extends AbstractType
{
    /**
     *
     */
    public function __construct(SplFileInfo $file = null)
    {
        $this->file = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (is_null($this->file)) {
            $builder->add('fileName', 'text', array(
                'required' => true,
                'label' => 'file_form.file_name_label',
                'translation_domain' => 'SplicedCmsBundle',
            ));
        }
        $builder->add('content', 'textarea', array(
            'required' => false,
            'label' => 'file_form.content_label',
            'translation_domain' => 'SplicedCmsBundle',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'file';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }
}
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

class FolderFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('folderName', 'text', array(
            'required' => true,
            'label' => 'folder_form.folder_name_label',
            'translation_domain' => 'SplicedCmsBundle',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'folder';
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
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

use Spliced\Bundle\CmsBundle\Model\TemplateExtensionImplementerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Spliced\Bundle\CmsBundle\Entity\Template;

class TemplateExtensionSettingsFormType extends AbstractType
{
    
    private $template;
    
    private $extension;
    
    /**
     *
     */
    public function __construct(Template $template, TemplateExtensionImplementerInterface $extension = null)
    {
        $this->template = $template;
        $this->extension = $extension;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->extension->buildSettingsForm($builder, $options);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'template_extension_settings';
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
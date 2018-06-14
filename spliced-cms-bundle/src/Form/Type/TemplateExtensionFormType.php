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

class TemplateExtensionFormType extends AbstractType
{

    private $template;

    private $extension;

    private $extensions;

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
        if (!$this->extension) {
            $builder->add('extensionKey', 'choice', array(
                'required' => true,
                'choices' => $this->getExtensionChoices(),
                'label' => 'template_extension_form.extension_key_label',
                'translation_domain' => 'SplicedCmsBundle',
                'empty_value' => 'Select an Extension'
            ));
            return;
        }
        $builder->add('settings', new TemplateExtensionSettingsFormType($this->template, $this->extension));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'template_extension';
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\TemplateExtension',
        ));
    }

    /**
     * @param mixed $extensions
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
    
    protected function getExtensionChoices()
    {
        $return = array();
        foreach ($this->getExtensions() as $extension) {
            $return[$extension->getKey()] = $extension->getName().' v'.$extension->getVersion();
        }
        return $return;
    }
}
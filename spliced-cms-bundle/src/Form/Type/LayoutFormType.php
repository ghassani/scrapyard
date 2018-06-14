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

use Spliced\Bundle\CmsBundle\Manager\SiteManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spliced\Bundle\CmsBundle\Entity\Layout;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

class LayoutFormType extends AbstractType
{
    
    private $layout;

    /**
     *
     */
    public function __construct(SiteManager $siteManager, Layout $layout = null)
    {
        $this->siteManager = $siteManager;
        $this->layout = $layout;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'layout_form.name_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('layoutKey', 'text', array(
                'label' => 'layout_form.layout_key_label',
                'translation_domain' => 'SplicedCmsBundle',
                'constraints' => array(
                    new Assert\Regex(array(
                        'pattern' => '/[^A-Za-z0-9_\-]/',
                        'match' => false,
                        'message' => 'Key can only contain alpha numeric characters (A-Z, 0-9), hyphens (-), and underscores(_)',
                    ))
                )
            ))
            ->add('template', new TemplateFormType($this->layout->getTemplate()), array(
                'label' => 'layout_form.template_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))->add('contentPageTemplate', new TemplateFormType($this->layout->getTemplate()), array(
                'label' => 'layout_form.content_page_template_label',
                'translation_domain' => 'SplicedCmsBundle',
            ));
            if (!$this->siteManager->getCurrentAdminSite()){
                $builder->add('site', 'entity', array(
                    'label' => 'layout_form.site_label',
                    'translation_domain' => 'SplicedCmsBundle',
                    'class' => 'SplicedCmsBundle:Site',
                    'required' => true,
                ));
            }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'layout';
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\Layout',
        ));
    }
}
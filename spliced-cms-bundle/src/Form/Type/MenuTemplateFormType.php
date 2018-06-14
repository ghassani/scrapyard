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
use Spliced\Bundle\CmsBundle\Entity\MenuTemplate;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Spliced\Bundle\CmsBundle\Manager\SiteManager;

class MenuTemplateFormType extends AbstractType
{

    private $menuTemplate;

    /**
     *
     */
    public function __construct(MenuTemplate $menuTemplate = null)
    {
        $this->menuTemplate = $menuTemplate;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'menu_template_form.name_label',
                'translation_domain' => 'SplicedCmsBundle',
            ))
            ->add('template', new TemplateFormType($this->menuTemplate->getTemplate()), array(
                'label' => 'menu_template_form.template_label',
                'translation_domain' => 'SplicedCmsBundle',
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'menu_template';
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CmsBundle\Entity\MenuTemplate',
        ));
    }
}
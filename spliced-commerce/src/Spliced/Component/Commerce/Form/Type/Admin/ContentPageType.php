<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ContentPageType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContentPageType extends AbstractType
{
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('pageTitle', 'text', array('required' => false))
            ->add('pageLayout', 'text', array('required' => false))
            ->add('urlSlug', 'text')
            ->add('metaDescription', 'textarea', array('required' => false))
            ->add('metaKeywords', 'textarea', array('required' => false))
            ->add('content', 'textarea', array('attr' => array( 'class' => 'wysiwyg')))
            ->add('isActive', 'choice', array('expanded' => true, 'choices' => array('No','Yes')));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_content_page';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CONTENT_PAGE),
        ));
    }
}
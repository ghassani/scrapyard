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
use Spliced\Component\Commerce\Form\Field\KeyValueType;

/**
 * ProductContentType
 * 
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductContentType extends AbstractType
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
            ->add('language', 'language', array('required' => true, 'label' => 'Language'))
            ->add('name', 'text', array('required' => false, 'label' => 'Product Name'))
            ->add('metaDescription', 'textarea', array('required' => false, 'label' => 'META Description'))
            ->add('metaKeywords', 'textarea', array('required' => false, 'label' => 'META Keywords'))
            ->add('shortDescription', 'textarea', array('required' => false, 'label' => 'Short Description'))
            ->add('longDescription', 'textarea', array('required' => false, 'label' => 'Long Description'))
            ->add('viewLayout', 'text', array('required' => false))
            ->add('viewStylesheets', 'collection', array(
                'type' => 'text',
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))                                
            ->add('viewJavascripts', 'collection', array(
                'type' => 'text',
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_data';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_CONTENT),
        ));
    }
}

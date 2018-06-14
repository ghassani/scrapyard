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
use Spliced\Component\Commerce\Model\CategoryInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * CategoryType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class CategoryType extends AbstractType
{
    
    /**
     * Constructor
     * 
     * @param CategoryInterface $category
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(CategoryInterface $category, ConfigurationManager $configurationManager)
    {
        $this->category = $category;
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * getCategory
     *
     * @return CategoryInterface
     */
    protected function getCategory()
    {
        return $this->category;
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
          ->add('parent', null, array('required' => false))
          ->add('name', 'text', array('required' => true))
          ->add('urlSlug', 'text', array('required' => true))
          ->add('position', 'number', array('required' => false, 'data' => 0))
          ->add('isActive', 'choice', array(
              'required' => false,
            'label' => 'Active',
            'empty_value' => '',
            'choices' => array('1' => 'Yes','0' => 'No'), 
            'multiple' => false,
              'data' => 1,
           ))
           ->add('pageTitle', 'text', array('required' => false))
           ->add('description', 'textarea', array('required' => false))
           ->add('headTitle', 'text', array('required' => false))
           ->add('metaDescription', 'textarea', array('required' => false))
           ->add('metaKeywords', 'textarea', array('required' => false));            
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_category';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CATEGORY),
        ));
    }
}
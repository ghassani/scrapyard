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
use Spliced\Component\Commerce\Model\ProductInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductImageType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductImageType extends AbstractType
{

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager, ProductInterface $product)
    {
        $this->configurationManager = $configurationManager;
        $this->product = $product;
    }
    
    /**
     * getProduct
     *
     * @return ProductInterface
     */
    protected function getProduct()
    {
        return $this->product;
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
          ->add('uploadedImage', 'file', array('required' => true)) 
          ->add('isMain', 'checkbox', array('required' => false, 'value' => 1));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_image';
    }
    

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_IMAGE),
        ));
    }
}

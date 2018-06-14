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
 * ProductRouteType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductRouteType extends AbstractType
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
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('requestPath')
          ->add('targetPath', 'text', array('data' => 'SplicedCommerceBundle:Product:view'));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_route';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE),
        ));
    }
}

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
use Spliced\Component\Commerce\Model\ManufacturerInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ManufacturerType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ManufacturerType extends AbstractType
{
    
    /**
     * Constructor
     *
     * @param ManufacturerInterface $manufacturer
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ManufacturerInterface $manufacturer, ConfigurationManager $configurationManager)
    {
        $this->manufacturer = $manufacturer;
        $this->configurationManager = $configurationManager;
    }

    /**
     * getManufacturer
     *
     * @return ManufacturerInterface
     */
    protected function getManufacturer()
    {
        return $this->manufacturer;
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
            ->add('name')               
        ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_manufacturer';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_MANUFACTURER),
        ));
    }
}

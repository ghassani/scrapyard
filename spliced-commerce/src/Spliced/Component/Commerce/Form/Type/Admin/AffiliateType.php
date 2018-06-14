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
use Spliced\Component\Commerce\Model\AffiliateInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * AffiliateType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class AffiliateType extends AbstractType
{
    
    /**
     * Constructor
     *
     * @param AffiliateInterface $affiliate
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(AffiliateInterface $affiliate, ConfigurationManager $configurationManager)
    {
        $this->affiliate = $affiliate;
        $this->configurationManager = $configurationManager;
    }

    /**
     * getAffiliate
     *
     * @return AffiliateInterface
     */
    protected function getAffiliate()
    {
        return $this->affiliate;
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
            ->add('website', 'url')
            ->add('orderPrefix', 'text', array('required' => false))
            ->add('referrerUrls', 'collection', array(
                'type' => new AffiliateReferrerType(),
                'allow_add' => true,
                'by_reference' => true,
                'allow_delete' => true,
                'required' => false,
            ))
            ->add('isComissioned', 'checkbox', array('required' => false, 'value' => true))
            ->add('isActive', 'checkbox', array('required' => false, 'value' => true))                  
        ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_affiliate';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_AFFILIATE),
        ));
    }
}

<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Form\Type;

use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigurationItemFormType extends AbstractType
{
    public function __construct(ConfigurationManagerInterface $configurationManager, ConfigurationItemInterface $item = null)
    {
        $this->configurationManager =  $configurationManager;
        $this->item = $item;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('key', 'text', array(
                'required' => true,
                'label' => 'configuration_item_filter_form.key_label',
                'translation_domain' => 'SplicedConfigurationBundle',
            ))->add('type', 'choice', array(
                'required' => true,
                'label' => 'configuration_item_filter_form.type_label',
                'translation_domain' => 'SplicedConfigurationBundle',
                'empty_value' => 'Select a Type',
                'choices' => $this->getTypeChoices(),
            ));
        if ($this->item && $this->item->getType()) {
            $typeHandler = $this->configurationManager->getType($this->item->getType());
            if (!$typeHandler) {
                die('NONE FOR '. $this->item->getType());//@TODO
            }
            $typeHandler->buildForm($this->item, $builder);
        }
    }

    protected function getTypeChoices()
    {
        $types = $this->configurationManager->getTypes();
        $return = array();
        foreach ($types as $type) {
            if ($type->getDescription()) {
                $return[$type->getKey()] = sprintf('%s - %s', $type->getName(), strip_tags($type->getDescription()));
            } else {
                $return[$type->getKey()] = $type->getName();
            }
        }
        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'configuration_item';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\ConfigurationBundle\Entity\Configuration',
        ));
    }
    
}
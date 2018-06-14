<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration\Type;

use Spliced\Component\Commerce\Model\ConfigurationInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * CountriesType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CountriesType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'countries';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getApplicationValue($value)
    {
        return unserialize($value);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDatabaseValue($value)
    {
        return serialize($value);
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(ConfigurationInterface $configData, FormBuilderInterface $form)
    {
        $form->add($configData->getFormSafeKey(), 'country', array(
            'label' => $configData->getLabel() ? $configData->getLabel() : null,
            'data' => $this->getApplicationValue($configData->getValue()),
            'multiple' => true,
            'required' => $configData->isRequired(),
            'empty_value' => $configData->isRequired() ? ' ' : null,
        ));
    }
}

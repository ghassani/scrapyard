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
 * LocaleType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LocaleType implements TypeInterface
{
    /**
     * {@inheritDoc}
    */
    public function getName()
    {
        return 'locale';
    }
    
    /**
     * {@inheritDoc}
    */
    public function getApplicationValue($value)
    {
        return $value;
    }
    
    /**
     * {@inheritDoc}
    */
    public function getDatabaseValue($value)
    {
        return $value;
    }
    
    /**
     * {@inheritDoc}
    */
    public function buildForm(ConfigurationInterface $configData, FormBuilderInterface $form)
    {
        $form->add($configData->getFormSafeKey(), 'locale', array(
            'label' => $configData->getLabel() ? $configData->getLabel() : null,
            'data' => $this->getApplicationValue($configData->getValue()),
            'required' => $configData->isRequired(),
            'empty_value' => $configData->isRequired() ? ' ' : null,
        ));
    }
}

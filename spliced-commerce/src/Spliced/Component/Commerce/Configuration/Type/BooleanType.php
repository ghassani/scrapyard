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
 * BooleanType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class BooleanType implements TypeInterface
{
    /**
     * {@inheritDoc}
    */
    public function getName()
    {
        return 'boolean';
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
        $form->add($configData->getFormSafeKey(), 'choice', array(
            'label' => $configData->getLabel() ? $configData->getLabel() : null,
            'data' => $this->getApplicationValue($configData->getValue()),
            'expanded' => false,
            'choices' => array(1 => 'Yes', 0 => 'No'),
            'required' => $configData->isRequired(),
        ));
    }
}

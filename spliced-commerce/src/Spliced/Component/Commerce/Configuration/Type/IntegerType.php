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
 * IntegerType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class IntegerType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'integer';
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
        $form->add($configData->getFormSafeKey(), 'text', array(
            'label' => $configData->getLabel() ? $configData->getLabel() : null,
            'data' => $this->getApplicationValue($configData->getValue()),
            //'precision' => 0,
            'required' => $configData->isRequired(),
        ));
    }
}

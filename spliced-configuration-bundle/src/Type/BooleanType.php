<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Type;

use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface;
use Spliced\Bundle\ConfigurationBundle\Model\TypeInterface;

/**
 * BooleanType
 */
class BooleanType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Boolean';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'boolean';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return 'A true/false boolean value';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm($item, $builder)
    {
        $builder->add('value', 'choice', array(
            'required' => false,
            'choices' => array(1 => 'Yes', 0 => 'No'),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDatabase($value)
    {
        return (bool) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToParameter($value)
    {
        return (bool) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDisplay($value)
    {
        return ((bool) $value) ? 'Yes' : 'No';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFormTemplatePath()
    {
        return null;
    }
}
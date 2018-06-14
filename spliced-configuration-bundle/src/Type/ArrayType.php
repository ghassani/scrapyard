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

use Spliced\Bundle\ConfigurationBundle\Form\Type\ArrayFormType;
use Spliced\Bundle\ConfigurationBundle\Form\Type\KeyValueFieldType;
use Spliced\Bundle\ConfigurationBundle\Form\Type\KeyValueFormType;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface;
use Spliced\Bundle\ConfigurationBundle\Model\TypeInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
/**
 * ArrayType
 */
class ArrayType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Array';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'array';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return 'An array or strings';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm($item, $builder)
    {
        $builder->add('value', new KeyValueFieldType(), array());
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDatabase($value)
    {
        if (!is_array($value)){
            return $value;
        }
        return serialize($value);
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToParameter($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return unserialize($value);
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDisplay($value)
    {
        if (!is_array($value)){
            $value = unserialize($value);
        }
        return '<pre>'.json_encode($value, JSON_PRETTY_PRINT).'</pre>';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFormTemplatePath()
    {
        return null;
    }
}
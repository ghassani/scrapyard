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
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;

/**
 * FloatType
 */
class FloatType implements TypeInterface
{
    const PRECISION = 2;

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Float';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'float';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return 'A decimal float value';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm($item, $builder)
    {
        $builder->add('value', 'number', array(
            'required' => true,
            'precision' => static::PRECISION,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDatabase($value)
    {
        return (float) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToParameter($value)
    {
        return (float) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDisplay($value)
    {
        return number_format($value, static::PRECISION, '.', '');
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFormTemplatePath()
    {
        return null;
    }
}
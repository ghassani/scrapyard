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
 * IntegerType
 */
class IntegerType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Integer';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'integer';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return 'A whole number value';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm($item, $builder)
    {
        $builder->add('value', 'integer', array(
            'required' => true,
            'precision' => 0,
            'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_DOWN
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDatabase($value)
    {
        return (int) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToParameter($value)
    {
        return (int) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDisplay($value)
    {
        return (string) $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFormTemplatePath()
    {
        return null;
    }
}
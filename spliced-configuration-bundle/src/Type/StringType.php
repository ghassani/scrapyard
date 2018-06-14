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
 * StringType
 */
class StringType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'String';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'string';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return 'A short string value';
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm($item, $builder)
    {
        $builder->add('value', 'text', array(
            'required' => false
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function transformValueToDatabase($value)
    {
        return (string) $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function transformValueToParameter($value)
    {
        return (string) $value;
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
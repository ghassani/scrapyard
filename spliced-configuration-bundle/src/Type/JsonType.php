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
 * JsonType
 */
class JsonType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'JSON String';
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'json';
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return 'A JSON string that will be encoded on save and decoded into an array on load.';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm($item, $builder)
    {
        $builder->add('value', 'textarea', array(
            'required' => true
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDatabase($value)
    {
        return json_encode($value);
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToParameter($value)
    {
        return json_decode($value, true);
    }

    /**
     * {@inheritDoc}
     */
    public function transformValueToDisplay($value)
    {
        if (!is_array($value)){
            $value = json_decode($value, true);
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
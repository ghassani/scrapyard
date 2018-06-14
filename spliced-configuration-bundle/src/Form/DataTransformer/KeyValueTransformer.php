<?php
/*
* This file is part of the SplicedConfigurationBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\ConfigurationBundle\Form\DataTransformer;

use Burgov\Bundle\KeyValueFormBundle\KeyValueContainer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class KeyValueTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        return $value;
    }
    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        $return = array();
        if (!is_array($value)){
            return array();
        }
        foreach ($value as $k => $v) {
            $return[$v['key']] = $v['value'];
        }
        return $return;
    }
}
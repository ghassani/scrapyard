<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Doctrine\ODM\MongoDB\Mapping\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * ConfigurationValue
 * 
 * Annotation mapping for ConfigurationValueType
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @Annotation
 */
class ConfigurationValue extends AbstractField
{
    public $type = 'configuration_value';
}
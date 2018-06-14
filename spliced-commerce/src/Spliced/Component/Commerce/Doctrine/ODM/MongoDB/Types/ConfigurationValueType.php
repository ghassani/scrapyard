<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Doctrine\ODM\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;

/**
 * ConfigurationValueType
 * 
 * MongoDB Field Type for Configuration Field value.
 * 
 * Values can be either an array or a string, so we need to
 * account for that. 
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationValueType extends Type
{
    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value)
    {
        return $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value)
    {
        return $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function closureToPHP()
    {
        return '$return = $value;';
    }

    /**
     * {@inheritDoc}
     */
    public function closureToMongo()
    {
        return '$return = $value;';
    }

}
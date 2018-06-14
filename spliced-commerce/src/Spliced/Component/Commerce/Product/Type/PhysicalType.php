<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product\Type;

/**
 * PhysicalType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class PhysicalType implements TypeInterface
{
    /**
     * @const TYPE_CODE
     * 
     * A unique integer representing this product type
     */
    const TYPE_CODE = 1;
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'physical';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return 'Physical';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTypeCode()
    {
        return static::TYPE_CODE;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isShippable()
    {
        return true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isDownloadable()
    {
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isElectronicDelivery()
    {
        return false;
    }

}
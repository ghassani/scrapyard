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
 * DigitalType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class DigitalType implements TypeInterface
{
    /**
     * @const TYPE_CODE
     * 
     * A unique integer representing this product type
     */
    const TYPE_CODE = 3;
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'digital';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return 'Digital';
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
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isElectronicDelivery()
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

}
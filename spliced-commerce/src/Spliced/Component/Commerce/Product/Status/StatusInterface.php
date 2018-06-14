<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product\TypeHandler;

/**
 * StatusInterface
 * 
 * All Product Status's must implement this interface. You will
 * also need to register the statuses by tagging your status services
 * with commerce.product_status
 * 
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface StatusInterface
{
    /**
     * getName
     *
     * A unique name for this status to be identified by
     *
     * @return string
     */
    public function getName();
    
    /**
     * getLabel
     * 
     * A string to display to the user identifying 
     * this status in a friendly, human understandable way
     * 
     * @return string
     */
    public function getLabel();
    
    /**
     * isAvailableForPurchase
     *
     * Whether or not this status is available for purchase
     *
     * @return bool
     */
    public function isAvailableForPurchase();
    
}
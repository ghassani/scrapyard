<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

/**
 * OrderInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderInterface
{

    /**
     * Constants of common order statuses
     */
    const STATUS_INCOMPLETE         = 'incomplete';
    const STATUS_PENDING            = 'pending';
    const STATUS_PROCESSING         = 'processing';
    const STATUS_BACKORDER            = 'backorder';
    const STATUS_SHIPPED            = 'shipped';
    const STATUS_SHIPPING            = 'shipping';
    const STATUS_PARTIALLY_SHIPPED    = 'partially_shipped';
    const STATUS_PARTIALLY_PAYED    = 'partially_payed';
    const STATUS_RETURNED             = 'returned';
    const STATUS_PARTIALLY_RETURNED = 'partially_returned';
    const STATUS_REFUNDED             = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATUS_CANCELLED             = 'cancelled';
    const STATUS_COMPLETE            = 'complete';
    const STATUS_DECLINED            = 'declined';
    const STATUS_COMPLETE_DECLINED    = 'complete_declined'; // this is for pushing through declined orders
    const STATUS_FRAUD                 = 'fraud';
    const STATUS_ABANDONED             = 'abandoned';
    
    
    /**
     * toString
     */
    public function __toString();

    /**
     * getOrderNumber
     */
    public function getOrderNumber();

    /**
     * generateProtectCode
     *
     * Generates a random string from an md5 hash
     * for use in decrypting order sensitive data
     */
    public function generateProtectCode();
}

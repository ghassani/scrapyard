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
 * OrderPaymentMemoInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderPaymentMemoInterface
{
    /**
     * Set createdBy
     *
     * @param  string           $createdBy
     * @return OrderPaymentMemo
     */
    public function setCreatedBy($createdBy);
    
    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy();
    
    /**
     * Set memo
     *
     * @param  string           $memo
     * @return OrderPaymentMemo
     */
    public function setMemo($memo);
    
    /**
     * Get memo
     *
     * @return string
     */
    public function getMemo();
    
    /**
     * Set memoData
     *
     * @param  array            $memoData
     * @return OrderPaymentMemo
     */
    public function setMemoData($memoData);
    
    /**
     * Get memoData
     *
     * @return array
     */
    public function getMemoData();
    
    /**
     * Set previousStatus
     *
     * @param  string           $previousStatus
     * @return OrderPaymentMemo
     */
    public function setPreviousStatus($previousStatus);
    
    /**
     * Get previousStatus
     *
     * @return string
     */
    public function getPreviousStatus();
    /**
     * Set changedStatus
     *
     * @param  string           $changedStatus
     * @return OrderPaymentMemo
     */
    public function setChangedStatus($changedStatus);
    
    /**
     * Get changedStatus
     *
     * @return string
     */
    public function getChangedStatus();
    
    /**
     * Get payment
     *
     * @return OrderPaymentInterface 
     */
    public function getPayment();
    
    /**
     * Set payment
     *
     * @param OrderPaymentInterface $payment
     */
    public function setPayment(OrderPaymentInterface $payment);
    
    /**
     * getUpdatedAt
     *
     * @return DateTime $updatedAt
     */
    public function getUpdatedAt();
    
    /**
     * setUpdatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt);
}

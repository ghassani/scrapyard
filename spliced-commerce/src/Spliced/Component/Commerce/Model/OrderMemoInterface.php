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
 * OrderMemoInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderMemoInterface
{
    /**
     * Set createdBy
     *
     * @param  string           $createdBy
     * @return OrderMemoInterface
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
     * @return OrderMemoInterface
     */
    public function setMemo($memo);
    
    /**
     * Get memo
     *
     * @return string
     */
    public function getMemo();
    
    /**
     * Set setNotificationType
     *
     * @param  string           $notificationType
     * @return OrderMemoInterface
     */
    public function setNotificationType($notificationType);
    
    /**
     * Get getNotificationType
     *
     * @return string
     */
    public function getNotificationType();
    
    /**
     * Get order
     *
     * @return OrderInterface 
     */
    public function getOrder();
    
    /**
     * Set order
     *
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order);
    
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
    
    /**
     * Set memoData
     *
     * @param  array            $memoData
     * @return OrderMemoInterface
     */
    public function setMemoData($memoData);
    
    /**
     * Get memoData
     *
     * @return array
    */
    public function getMemoData();
}

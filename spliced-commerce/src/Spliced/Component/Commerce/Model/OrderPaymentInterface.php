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

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrderPaymentInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface OrderPaymentInterface
{
    /**
     * Set paymentMethod
     *
     * @param  string       $paymentMethod
     * @return OrderPayment
     */
    public function setPaymentMethod($paymentMethod);
    
    /**
     * Get paymentMethod
     *
     * @return string
     */
    public function getPaymentMethod();
    
    /**
     * Set paymentStatus
     *
     * @param  integer      $paymentStatus
     * @return OrderPayment
     */
    public function setPaymentStatus($paymentStatus);
    
    /**
     * Get paymentStatus
     *
     * @return integer
     */
    public function getPaymentStatus();

    /**
     * Get order
     *
     * @return OrderInterface $order
     */
    public function getOrder();

    /**
     * Set order
     *
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order);
    
    /**
     * Set creditCard
     *
     * @param CustomerCreditCard $payment
     */
    public function setCreditCard(CustomerCreditCardInterface $creditCard = null);
    
    /**
     * Get creditCard
     *
     * @return CustomerCreditCard
     */
    public function getCreditCard();
    
    /**
     * Set memo
     *
     * @param Collection $memos
     */
    public function setMemos(Collection $memo = null);
    
    /**
     * Get memos
     *
     * @return Collection
     */
    public function getMemos();
    
    /**
     * Add memo
     *
     * @return addMemo
     */
    public function addMemo(OrderPaymentMemoInterface $memo);
}

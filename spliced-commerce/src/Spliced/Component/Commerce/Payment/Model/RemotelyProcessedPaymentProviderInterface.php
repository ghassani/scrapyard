<?php

namespace Spliced\Component\Commerce\Payment\Model;

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Symfony\Component\HttpFoundation\Request;

interface RemotelyProcessedPaymentProviderInterface extends PaymentProviderInterface
{

    /**
     * startRemotePayment
     * 
     * Handles starting remote payment, returing a RedirectResponse
     * 
     * @param OrderInterface $order
     * 
     * @return Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function startRemotePayment(OrderInterface $order);

    /**
     * cancelRemotePayment
     *
     * Handles canceling the payment if the customer decides to
     * 
     * @param OrderInterface $order
     * @param Request $request
     *
     */
    public function cancelRemotePayment(OrderInterface $order, Request $request);
    
    /**
     * completeRemotePayment
     * 
     * Handles completing the payment after the merchant has approved it
     *
     * @param OrderInterface $order
     *
     */
    public function completeRemotePayment(OrderInterface $order, Request $request);
    
    /**
     * updateRemotePayment
     * 
     * Handles updating the remote payment when changes occour, if supported by merchant
     *
     * @param OrderInterface $order
     *
     */
    public function updateRemotePayment(OrderInterface $order, Request $request);
}

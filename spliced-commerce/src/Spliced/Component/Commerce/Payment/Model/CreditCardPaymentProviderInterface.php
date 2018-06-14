<?php

namespace Spliced\Component\Commerce\Payment\Model;

use Spliced\Component\Commerce\Model\OrderInterface;

interface CreditCardPaymentProviderInterface
{
    /**
     * getClient
     *
     * @return GatewayClient
     */
    public function getClient();

    /**
     * chargeOrder
     *
     * @param OrderInterface $order
     */
    public function chargeOrder(OrderInterface $order);

    /**
     * voidOrder
     *
     * @param OrderInterface $order
     */
    public function voidOrder(OrderInterface $order);

    /**
     * refundOrder
     *
     * @param OrderInterface $order
     */
    public function refundOrder(OrderInterface $order);

    /**
     * voidOrder
     *
     * @param authorizeOrder $order
     */
    public function authorizeOrder(OrderInterface $order);

    /**
     * savesCreditCard
     *
     * @return bool
     */
    public function savesCreditCard();

    /**
     *
     */
    public function setEncryptionManager($manager);

    /**
     *
     */
    public function getEncryptionManager();
}

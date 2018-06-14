<?php

namespace Spliced\Component\Commerce\Payment\Model;

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;

interface PaymentProviderInterface extends ConfigurableInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * acceptsCreditCards
     *
     * @return bool
     */
    public function acceptsCreditCards();
    
    /**
     * isRemotelyProcessed
     *
     * @return bool
     */
    public function isRemotelyProcessed();


    /**
     * isEnabled
     *
     * @return bool
     */
    public function isEnabled();
    
    /**
     * process
     *
     * @param OrderInterface $order
     *
     * @throws PaymentErrorException
     * @throws PaymentDeclinedException
     * @return OrderInterface
     */
    public function process(OrderInterface $order);
}

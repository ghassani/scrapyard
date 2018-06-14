<?php

namespace Spliced\Component\Commerce\Payment;

use Spliced\Component\Commerce\Model\OrderInterface;

class PaymentErrorException extends \Exception
{
    protected $order;

    public function __construct(OrderInterface $order, $message, $code = 0)
    {
        $this->order = $order;
        parent::__construct($message,$code);
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getToStep()
    {
        return $this->getCode();
    }
}

<?php

namespace Spliced\Component\Commerce\Payment\Phone;

use Spliced\Component\Commerce\Payment\Model\PaymentProvider;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\Order;

class PhonePayment extends PaymentProvider
{
    protected $name = 'phone';

    /**
     * {@inheritDoc}
     */
    public function process(OrderInterface $order)
    {
        $order->setOrderStatus($this->getOption('checkout_complete_status', Order::STATUS_PENDING));

        $order->getPayment()->setPaymentStatus(Order::STATUS_PENDING);
        
        return $order;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.payment.phone';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        $i = 0;
        return array_merge(parent::getRequiredConfigurationFields(),array(
                // standard payment provider fields
                'enabled' => array(
                    'type' => 'boolean',
                    'value' => isset($this->defaultConfigurationValues['enabled']) ? $this->defaultConfigurationValues['enabled'] : null,
                    'label' => 'Enabled',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => 'Pay By Phone',
                    'position' => ++$i,
                    'required' => false,
                ),
                'position' => array(
                    'type' => 'integer',
                    'value' => isset($this->defaultConfigurationValues['position']) ? $this->defaultConfigurationValues['position'] : 0,
                    'label' => 'Position',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => 'Pay By Phone',
                    'position' => ++$i,
                    'required' => false,
                ),
                'checkout_complete_status' => array(
                    'type' => 'status',
                    'value' => isset($this->defaultConfigurationValues['checkout_complete_status']) ? $this->defaultConfigurationValues['checkout_complete_status'] : Order::STATUS_PROCESSING,
                    'label' => 'Order Status After Checkout Complete',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => 'Pay By Phone',
                    'position' => ++$i,
                    'required' => false,
                ),
                'label' => array(
                    'type' => 'string',
                    'value' => isset($this->defaultConfigurationValues['label']) ? $this->defaultConfigurationValues['label'] : null,
                    'label' => 'Label',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => 'Pay By Phone',
                    'position' => ++$i,
                    'required' => false,
                ),
                'label2' => array(
                    'type' => 'string',
                    'value' => isset($this->defaultConfigurationValues['label2']) ? $this->defaultConfigurationValues['label2'] : null,
                    'label' => 'Label 2',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => 'Pay By Phone',
                    'position' => ++$i,
                    'required' => false,
                ),
                'description' => array(
                    'type' => 'html',
                    'value' => isset($this->defaultConfigurationValues['description']) ? $this->defaultConfigurationValues['description'] : null,
                    'label' => 'Description',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => 'Pay By Phone',
                    'position' => ++$i,
                    'required' => false,
                ),
        ));
    }
}

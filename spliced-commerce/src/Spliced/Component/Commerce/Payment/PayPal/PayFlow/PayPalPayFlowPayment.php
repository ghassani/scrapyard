<?php

namespace Spliced\Component\Commerce\Payment\PayPal\PayFlow;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Payment\Model\PaymentProvider;
use Spliced\Component\Commerce\Payment\Model\CreditCardPaymentProviderInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\PayPal\PayFlow\Client;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;

class PayPalPayFlowPayment extends PaymentProvider implements CreditCardPaymentProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ConfigurationManager $configurationManager, OrderHelper $orderHelper)
    {
        parent::__construct($configurationManager, $orderHelper);
        //$this->client = new Client($this->getOption('transaction_key'),$this->getOption('login_key'),$this->getOption('test_mode'));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'paypal_payflow';
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.payment.paypal.payflow';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        $i = 0;
        return array_merge(parent::getRequiredConfigurationFields(),array(
            'checkout_charge_type' => array(
                 'type' => 'boolean',
                 'value' => isset($this->defaultConfigurationValues['checkout_charge_type']) ? $this->defaultConfigurationValues['checkout_charge_type'] : 'Authorize',
                 'label' => 'Charge Type On Checkout',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
            ),
            'username' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['username']) ? $this->defaultConfigurationValues['username'] : null,
                 'label' => 'Username',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
            ),
            'password' => array(
                 'type' => 'encrypted',
                 'value' => isset($this->defaultConfigurationValues['password']) ? $this->defaultConfigurationValues['password'] : null,
                 'label' => 'Password',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
            ),
            'vendor' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['vendor']) ? $this->defaultConfigurationValues['vendor'] : null,
                 'label' => 'Vendor',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
            ),
            'partner' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['partner']) ? $this->defaultConfigurationValues['partner'] : null,
                 'label' => 'Partner',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
            'test_mode' => array(
                 'type' => 'boolean',
                 'value' => isset($this->defaultConfigurationValues['test_mode']) ? $this->defaultConfigurationValues['test_mode'] : true,
                 'label' => 'Test Mode',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
            // standard fields for credit card provider
            'success_on_decline' => array(
                 'type' => 'boolean',
                 'value' => isset($this->defaultConfigurationValues['success_on_decline']) ? $this->defaultConfigurationValues['success_on_decline'] : null,
                 'label' => 'Success On Decline',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
             'success_on_decline_attempts' => array(
                  'type' => 'integer',
                  'value' => isset($this->defaultConfigurationValues['success_on_decline_attempts']) ? $this->defaultConfigurationValues['success_on_decline_attempts'] : 1,
                  'label' => 'Success On Decline After x Attempts',
                  'help' => '',
                  'group' => 'Payment',
                  'child_group' => 'PayPal PayFlow',
                  'position' => ++$i,
                  'required' => false,
             ),
             'success_on_decline_status' => array(
                  'type' => 'status',
                  'value' => isset($this->defaultConfigurationValues['success_on_decline_status']) ? $this->defaultConfigurationValues['success_on_decline_status'] : OrderInterface::STATUS_PENDING,
                  'label' => 'Success On Decline After x Attempts',
                  'help' => '',
                  'group' => 'Payment',
                  'child_group' => 'PayPal PayFlow',
                  'position' => ++$i,
                  'required' => false,
             ),
             // standard payment provider fields
             'enabled' => array(
                 'type' => 'boolean',
                 'value' => isset($this->defaultConfigurationValues['enabled']) ? $this->defaultConfigurationValues['enabled'] : null,
                 'label' => 'Enabled',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
             'position' => array(
                 'type' => 'integer',
                 'value' => isset($this->defaultConfigurationValues['position']) ? $this->defaultConfigurationValues['position'] : 0,
                 'label' => 'Position',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
             'checkout_complete_status' => array(
                 'type' => 'status',
                 'value' => isset($this->defaultConfigurationValues['checkout_complete_status']) ? $this->defaultConfigurationValues['checkout_complete_status'] : OrderInterface::STATUS_PROCESSING,
                 'label' => 'Order Status After Checkout Complete',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
             'label' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['label']) ? $this->defaultConfigurationValues['label'] : null,
                 'label' => 'Label',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
             'label2' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['label2']) ? $this->defaultConfigurationValues['label2'] : null,
                 'label' => 'Label 2',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
             'description' => array(
                 'type' => 'html',
                 'value' => isset($this->defaultConfigurationValues['description']) ? $this->defaultConfigurationValues['description'] : null,
                 'label' => 'Description',
                 'help' => '',
                 'group' => 'Payment',
                 'child_group' => 'PayPal PayFlow',
                 'position' => ++$i,
                 'required' => false,
             ),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritDoc}
     */
    public function chargeOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function voidOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function refundOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function authorizeOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function savesCreditCard()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setEncryptionManager($manager)
    {
        $this->encryptionManager = $manager;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEncryptionManager()
    {
        return $this->encryptionManager;
    }
}

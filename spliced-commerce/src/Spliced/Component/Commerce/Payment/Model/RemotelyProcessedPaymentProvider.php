<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Payment\Model;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\Order;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;
use Spliced\Component\Commerce\Cart\CartManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * RemotelyProcessedPaymentProvider
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class RemotelyProcessedPaymentProvider extends PaymentProvider implements RemotelyProcessedPaymentProviderInterface
{
    protected $name = null;
    
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager, CartManager $cartManager, OrderHelper $orderHelper, RouterInterface $router)
    {
        $this->configurationManager = $configurationManager;
        $this->cartManager = $cartManager;
        $this->orderHelper = $orderHelper;
        $this->router = $router;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRouter()
    {
        return $this->router;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCartManager()
    {
        return $this->cartManager;
    }

    /**
     * {@inheritDoc}
     */
    public function process(OrderInterface $order)
    {
        throw new \RuntimeException('PaymentProvider::process must be implemented in the parent class.');
    }

    /**
     * {@inheritDoc}
     */
    public function startRemotePayment(OrderInterface $order)
    {
        throw new \RuntimeException('RemotelyProcessedPaymentProvider::startRemotePayment must be implemented in the parent class.');
    }

    /**
     * {@inheritDoc}
     */
    public function cancelRemotePayment(OrderInterface $order, Request $request)
    {
        throw new \RuntimeException('RemotelyProcessedPaymentProvider::cancelRemotePayment must be implemented in the parent class.');
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateRemotePayment(OrderInterface $order, Request $request)
    {
        throw new \RuntimeException('RemotelyProcessedPaymentProvider::cancelRemotePayment must be implemented in the parent class.');
    }
    
    /**
     * {@inheritDoc}
     */
    public function completeRemotePayment(OrderInterface $order, Request $request)
    {
        throw new \RuntimeException('RemotelyProcessedPaymentProvider::completeRemotePayment must be implemented in the parent class.');
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        $i = 0;
        return array(
            'enabled' => array(
                'type' => 'boolean',
                'value' => isset($this->defaultConfigurationValues['enabled']) ? $this->defaultConfigurationValues['enabled'] : null,
                'label' => 'Enabled',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'position' => array(
                'type' => 'integer',
                'value' => isset($this->defaultConfigurationValues['position']) ? $this->defaultConfigurationValues['position'] : null,
                'label' => 'Position',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'checkout_complete_status' => array(
                'type' => 'status',
                'value' => isset($this->defaultConfigurationValues['checkout_complete_status']) ? $this->defaultConfigurationValues['checkout_complete_status'] : Order::STATUS_PROCESSING,
                'label' => 'Order Status After Checkout Complete',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'label' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['label']) ? $this->defaultConfigurationValues['label'] : null,
                'label' => 'Label',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'label2' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['label2']) ? $this->defaultConfigurationValues['label2'] : null,
                'label' => 'Label 2',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'description' => array(
                'type' => 'html',
                'value' => isset($this->defaultConfigurationValues['description']) ? $this->defaultConfigurationValues['description'] : null,
                'label' => 'Description',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'continue_to_button_image' => array(
                'type' => 'url',
                'value' => isset($this->defaultConfigurationValues['continue_to_button_image']) ? $this->defaultConfigurationValues['continue_to_button_image'] : null,
                'label' => 'Continue To Image',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
            'continue_to_button_text' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['continue_to_button_text']) ? $this->defaultConfigurationValues['continue_to_button_text'] : null,
                'label' => 'Continue To Image',
                'help' => '',
                'group' => 'Payment',
                'child_group' => ucwords($this->getName()),
                'position' => ++$i,
                'required' => false,
            ),
        );
    }
    
}

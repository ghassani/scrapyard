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

/**
 * PaymentProvider
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class PaymentProvider implements PaymentProviderInterface
{
    protected $name = null;
    
    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager, OrderHelper $orderHelper, array $defaultConfigurationValues = array())
    {
        $this->configurationManager = $configurationManager;
        $this->orderHelper = $orderHelper;
        
        $this->defaultConfigurationValues = $defaultConfigurationValues;
    }

    /**
     * toString
     * 
     * @return string
     */
     public function __toString()
     {
         return $this->getLabel();    
     }
     
    /**
     * {@inheritDoc}
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderHelper()
    {
        return $this->orderHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->getConfigurationManager()->getByKeyExpression(sprintf('/^%s/',$this->getConfigPrefix()));
    }

    /**
     * {@inheritDoc}
     */
    public function getOption($key, $defaultValue = null)
    {
        return $this->getConfigurationManager()->get(sprintf('%s.%s',$this->getConfigPrefix(),$key),$defaultValue);
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getConfigPrefix();

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        $return = array();
        $i=0;
        if ($this->acceptsCreditCards()) {
            $return = array(
                'success_on_decline' => array(
                    'type' => 'boolean',
                    'value' => isset($this->defaultConfigurationValues['success_on_decline']) ? $this->defaultConfigurationValues['success_on_decline'] : null,
                    'label' => 'Success On Decline',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => ucwords($this->getName()),
                    'position' => ++$i,
                    'required' => false,
                ),   
                'success_on_decline_attempts' => array(
                   'type' => 'integer',
                   'value' => isset($this->defaultConfigurationValues['success_on_decline_attempts']) ? $this->defaultConfigurationValues['success_on_decline_attempts'] : 1,
                   'label' => 'Success On Decline After x Attempts',
                   'help' => '',
                   'group' => 'Payment',
                   'child_group' => ucwords($this->getName()),
                   'position' => ++$i,
                   'required' => false,
                ),
                'success_on_decline_status' => array(
                    'type' => 'status',
                    'value' => isset($this->defaultConfigurationValues['success_on_decline_status']) ? $this->defaultConfigurationValues['success_on_decline_status'] : Order::STATUS_PENDING,
                    'label' => 'Success On Decline After x Attempts',
                    'help' => '',
                    'group' => 'Payment',
                    'child_group' => ucwords($this->getName()),
                    'position' => ++$i,
                    'required' => false,
                ),
            );
        }

        return array_merge($return,array(
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
                'value' => isset($this->defaultConfigurationValues['position']) ? $this->defaultConfigurationValues['position'] : 0,
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
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        if (is_null($this->name)) {
            throw new \Exception('Payment Method Name Must Be Set');
        }

        return $this->name;
    }

    /**
     * getLabel
     */
    public function getLabel()
    {
        return $this->getOption('label');
    }

    /**
     * getLabel2
     */
    public function getLabel2()
    {
        return $this->getOption('label2');
    }

    /**
     * {@inheritDoc}
     */
    public function acceptsCreditCards()
    {
        return $this instanceof CreditCardPaymentProviderInterface;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isRemotelyProcessed()
    {
        return $this instanceof RemotelyProcessedPaymentProviderInterface;
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
    public function isEnabled()
    {
        return $this->getOption('enabled') ? true : false;
    }
    
    
}

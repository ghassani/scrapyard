<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout\CheckoutNotifier;

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Checkout\CheckoutNotifierSubscriberInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;

/**
 * ShopzillaCheckoutNotifierSubscriber
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShopzillaCheckoutNotifierSubscriber implements CheckoutNotifierSubscriberInterface, ConfigurableInterface
{

    const NAME = 'shopzilla';
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param OrderHelper $orderHelper
     */
    public function __construct(ConfigurationManager $configurationManager, OrderHelper $orderHelper)
    {
        $this->configurationManager = $configurationManager;
        $this->orderHelper = $orderHelper;
    }

    /**
     * getOrderHelper
     * 
     * @return OrderHelper
     */
    protected function getOrderHelper()
    {
        return $this->orderHelper;
    }
    

    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return static::NAME;     
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderHeadHtml(OrderInterface $order)
    {        
        return null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderTrackerHtml(OrderInterface $order)
    {        
        if(!$this->getOption('enabled') || !$this->getOption('merchant_id')){
            return null;
        }
        
        $merchantId = $this->getOption('merchant_id');
        
        $isRegisteredCustomer = $order->getCustomer() ? '1' : '0';
        
        $html = 
<<<EOF
        <script language="javascript">
        <!--
        var mid            = '{$merchantId}';
        var cust_type      = '{$isRegisteredCustomer}';
        var order_value    = '{$this->getOrderHelper()->getOrderTotal($order, true)}';
        var order_id       = '{$order->getId()}';
        var units_ordered  = '{$this->getOrderHelper()->getOrderTotalItems($order)}';
        //-->
        </script>
        <script language="javascript" src="https://www.shopzilla.com/css/roi_tracker.js"></script>
EOF;
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderBodyEndHtml(OrderInterface $order){        
        return null;
    }
    

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.checkout_notifier.shopzilla';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {

        return array(
            'enabled' => array(
                'type' => 'boolean',
                'value' => true,
                'label' => 'Enabled',
                'help' => '',
                'group' => 'Checkout Notifiers',
                'child_group' => 'Shopzilla',
                'position' => 1,
                'required' => false,
            ),
            'merchant_id' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'Merchant ID',
                'help' => '',
                'group' => 'Checkout Notifiers',
                'child_group' => 'Shopzilla',
                'position' => 2,
                'required' => false,
            ),    
        );
    
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
}

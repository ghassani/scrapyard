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

use Spliced\Component\Commerce\Checkout\CheckoutNotifierSubscriberInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;

/**
 * GoogleTrustedStoresCheckoutNotifierSubscriber
 * 
 * CheckoutNotifier for Google Trusted Stores Integration
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleTrustedStoresCheckoutNotifierSubscriber implements CheckoutNotifierSubscriberInterface, ConfigurableInterface
{

    const NAME = 'google_trusted_stores';
    
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
        if(!$this->getOption('enabled') || ! $this->getOption('trusted_store_id')){
            return null;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderTrackerHtml(OrderInterface $order)
    {
        if(!$this->getOption('enabled') || ! $this->getOption('trusted_store_id')){
            return null;
        }

        $trustedStoreId = $this->getOption('trusted_store_id');
        
        $shipDate = new \DateTime('now +1 day');
        
        $html = <<<EOF
        <div id="gts-order" style="display:none;">
            <!-- start order and merchant information -->
            <span id="gts-o-id">{$order->getId()}</span>
                <span id="gts-o-domain">{$this->getConfigurationManager()->get('commerce.store.url')}</span>
                <span id="gts-o-email">{$order->getEmail()}</span>
                <span id="gts-o-country">{$order->getBillingCountry()}</span>
                <span id="gts-o-currency">USD</span>
                <span id="gts-o-total">{$this->getOrderHelper()->getOrderTotal($order, true)}</span>
                <span id="gts-o-discounts">-0.0</span>
                <span id="gts-o-shipping-total">{$this->getOrderHelper()->getOrderShipping($order)}</span>
                <span id="gts-o-tax-total">{$this->getOrderHelper()->getOrderTax($order)}</span>
                <span id="gts-o-est-ship-date">{$shipDate->format('Y-m-d')}</span>
                <span id="gts-o-has-preorder">N</span>
                <span id="gts-o-has-digital">N</span>
              <!-- end order and merchant information -->
EOF;
     foreach ($order->getItems() as $item){
           $html .= <<<EOF
                <span class="gts-item">
                    <span class="gts-i-name">{$item->getName()}</span>
                    <span class="gts-i-price">{$item->getFinalPrice()}</span>
                    <span class="gts-i-quantity">{$item->getQuantity()}</span>
                    <span class="gts-i-prodsearch-country">US</span>
                    <span class="gts-i-prodsearch-language">en</span>
                    <span class="gts-i-prodsearch-store-id">{$trustedStoreId}</span>
                </span>
EOF;
    }

        $html .= '</div>';
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderBodyEndHtml(OrderInterface $order){
        if(!$this->getOption('enabled') || ! $this->getOption('trusted_store_id')){
            return null;
        }
        
        return null;
    }
    

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.checkout_notifier.google_trusted_stores';
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
                'child_group' => 'Google Trusted Stores',
                'position' => 1,
                'required' => false,
            ),
            'trusted_store_id' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'Trusted Store ID',
                'help' => 'The Google Trusted Store ID can be obtained by logging into your Google Trusted Store account in the upper right hand corner.',
                'group' => 'Checkout Notifiers',
                'child_group' => 'Google Trusted Stores',
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

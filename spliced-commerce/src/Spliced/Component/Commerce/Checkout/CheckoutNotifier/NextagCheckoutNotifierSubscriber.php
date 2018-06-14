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
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * NextagCheckoutNotifierSubscriber
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class NextagCheckoutNotifierSubscriber implements CheckoutNotifierSubscriberInterface, ConfigurableInterface
{
    
    const NAME = 'nextag';
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return static::NAME;     
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
    public function renderHeadHtml(OrderInterface $order)
    {
        return null;
    }
    
    /**
     * {@inheritDoc}
     */    
    public function renderTrackerHtml(OrderInterface $order)
    {        
        $html = <<<EOF
   <script type="text/javascript">
    <!--
    //NexTag ROI Optimizer Data
    var id = '{$this->getOption('merchant_id')}';
    var rev = '';
    var order = '{$order->getOrderNumber()}';
    //-->
    </script>
    <script type="text/javascript" src="https://imgsrv.nextag.com/imagefiles/includes/roitrack.js"></script>
EOF;

        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderBodyEndHtml(OrderInterface $order)
    {
        return null;
    }


    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.checkout_notifier.netxtag';
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
                'child_group' => 'Nextag',
                'position' => 1,
                'required' => false,
            ),
            'merchant_id' => array(
                'type' => 'string',
                'value' => null,
                'label' => 'Merchant ID',
                'help' => '',
                'group' => 'Checkout Notifiers',
                'child_group' => 'Nextag',
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

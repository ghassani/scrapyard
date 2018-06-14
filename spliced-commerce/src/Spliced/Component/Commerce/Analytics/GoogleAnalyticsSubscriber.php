<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Analytics;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * GoogleAnalyticsSubscriber
 * 
 * Subscriber for Google Analytics
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class GoogleAnalyticsSubscriber implements AnalyticsSubscriberInterface
{

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
     public function getConfigurationManager()
     {
         return $this->configurationManager;
     }
     
     
    /**
     * {@inheritDoc}
     */
     public function getName()
     {
         return 'google_analytics';
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
     public function getConfigPrefix()
     {
         return 'commerce.analytics.google';
     }
     
     /**
      * {@inheritDoc}
     */
     public function getRequiredConfigurationFields()
     {
     
         return array(
             'account_id' => array(
                 'type' => 'string', 
                 'value' => null,
                 'label' => 'Account ID',
                 'help' => 'The account ID of your Google Analytics Account',
                 'group' => 'Analytics',
                 'child_group' => 'Google',
                 'position' => 0,
                 'required' => false,
             ),
             'enabled' => array(
                 'type' => 'boolean',
                 'value' => true,
                 'label' => 'Enabled',
                 'help' => '',
                 'group' => 'Analytics',
                 'child_group' => 'Google',
                 'position' => 1,
                 'required' => false,
             ),    
         );
     }
     
    /**
     * {@inheritDoc}
     */
    public function renderTrackerHtml()
    {
        if(!$this->getOption('account_id')){
           return null; 
        }
        
        return <<<EOF
        <!-- BEGIN GOOGLE ANALYTICS CODEs -->
        <script type="text/javascript">
        //<![CDATA[
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '{$this->getOption('account_id')}']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        //]]>
        </script>
        <!-- END GOOGLE ANALYTICS CODE -->
EOF;
    }

}

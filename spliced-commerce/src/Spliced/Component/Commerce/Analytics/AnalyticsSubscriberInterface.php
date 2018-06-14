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

use Spliced\Component\Commerce\Configuration\ConfigurableInterface;

/**
 * AnalyticsSubscriberInterface
 * 
 * All analytics subscribers must implement this interface.
 * This interface specifies the methods used by all analytics
 * subscribers to render tracker HTML
 * 
 * This interface also extends ConfigurableInterface which allows
 * you do add configuration values which can be changed in the administration
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface AnalyticsSubscriberInterface extends ConfigurableInterface
{
    
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    public function getConfigurationManager();
    
    /**
     * getName
     * 
     * @return string
     */
    public function getName();
    
    /**
     * renderTrackerHtml
     * 
     * @return string
     */
    public function renderTrackerHtml();

}

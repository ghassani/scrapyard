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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * AnalyticsManager
 *
 * Handles the analytics subscribers that are registered, use to
 * get subscribers to render HTML for their trackers in the view.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class AnalyticsManager 
{
    /** @var ArrayCollection */
    protected $subscribers = array();
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->subscribers = new ArrayCollection();
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * addSubscriber
     * 
     * @param AnalyticsSubscriberInterface $subscriber
     */
    public function addSubscriber(AnalyticsSubscriberInterface $subscriber)
    {
        $this->subscribers->add($subscriber);
        return $this;
    }
    
    /**
     * getSubscribers
     * 
     * @return Collection
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }
    
    /**
     * setSubscribers
     *
     * @param Collection $subscribers
     */
    public function setSubscribers(Collection $subscribers)
    {
        $this->subscribers = $subscribers;
        return $this;
    }
    
    /**
     * removeSubscriber
     */
    public function removeSubscriber(AnalyticsSubscriberInterface $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
        return $this;
    }
}

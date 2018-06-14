<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * CheckoutNotifierManager
 * 
 * Handles all 3rd party integrations which allow you to send order information
 * for analytics purposes with various PPC providers, such as Google Trusted Stores,
 * Shopzilla, and more.
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutNotifierManager
{
    protected $notifiers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notifiers = new ArrayCollection();
    }
    
    /**
     * addNotifier
     * 
     * @param CheckoutNotifierSubscriberInterface $notifier
     */
    public function addNotifier(CheckoutNotifierSubscriberInterface $notifier)
    {
        $this->notifiers->set($notifier->getName(), $notifier);
        return $this;
    }
    
    /**
     * getNotifiers
     * 
     * @return ArrayCollection
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }
    
    /**
     * getNotifiers
     *
     * @return ArrayCollection
     */
    public function getNotifier()
    {
        return $this->notifiers;
    }
    
    /**
     * setNotifiers
     * 
     * @param ArrayCollection $notifiers
     */
    public function setNotifiers(ArrayCollection $notifiers)
    {
        $this->notifiers = $notifiers;
        return $this;
    }
}

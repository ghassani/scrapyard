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

/**
 * CheckoutNotifierSubscriberInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CheckoutNotifierSubscriberInterface
{
    
    /**
     * getName
     * 
     * Returns a unique name for the notifier
     * 
     * @return string
     */
    public function getName();
    
    /**
     * renderHeadHtml
     * 
     * Renders HTML for the HEAD of the Checkout Success Page
     * for the checkout notifier, if needed by the service.
     * 
     * @param OrderInterface $order
     * 
     * @return string
     */
    public function renderHeadHtml(OrderInterface $order);

    /**
     * renderTrackerHtml
     *
     * Renders HTML for the the actual tracker for the checkout
     * success page. Ideally supplying their api with whatever 
     * order information they need 
     *
     * @param OrderInterface $order
     *
     * @return string
     */
    public function renderTrackerHtml(OrderInterface $order);

    /**
     * renderBodyEndHtml
     *
     * Renders HTML for the END of the Checkout Success Page
     * for the checkout notifier, if needed by the service.
     *
     * @param OrderInterface $order
     *
     * @return string
     */
    public function renderBodyEndHtml(OrderInterface $order);
}

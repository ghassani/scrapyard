<?php

namespace Spliced\Component\Commerce\Amazon;

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Checkout\CheckoutNotifierSubscriberInterface;

class AmazonProductAdsCheckoutNotifierSubscriber implements CheckoutNotifierSubscriberInterface
{
    public function renderNotifierHtml(OrderInterface $order)
    {
        return '<p>Amazon Product Ads Checkout Notifier Code Here</p>';
    }

}

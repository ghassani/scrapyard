<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Spliced\Component\Commerce\Payment\Model\PaymentProviderInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
/**
 * CheckoutCompleteOnDeclineEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutCompleteOnDeclineEvent extends CheckoutEvent
{

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface &$order, PaymentProviderInterface $paymentProvider)
    {
        $this->paymentProvider = $paymentProvider;
        parent::__construct($order);
    }

    /**
     * getPaymentProvider
     *
     * @return PaymentProviderInterface
     */
    public function getPaymentProvider()
    {
        return $this->paymentProvider;
    }
}

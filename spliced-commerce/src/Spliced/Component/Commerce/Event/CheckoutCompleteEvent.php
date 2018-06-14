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

use Symfony\Component\HttpFoundation\Request;
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * CheckoutCompleteEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutCompleteEvent extends CheckoutEvent
{
    /** @var Request $request */
    protected $request;
    
    /** @var OrderInterface $order */
    protected $order;

    
    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order, Request $request)
    {
        $this->request = $request;
        parent::__construct($order);
    }
    
    /**
     * getRequest
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}

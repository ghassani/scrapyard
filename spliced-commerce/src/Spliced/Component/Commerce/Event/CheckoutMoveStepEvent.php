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

use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\CustomerInterface;

/**
 * CheckoutMoveStepEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutMoveStepEvent extends CheckoutEvent
{
    
    protected $currentStep;
    
    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order, $currentStep)
    {
        $this->currentStep = $currentStep;
        parent::__construct($order);
    }
    
    /**
     * getCurrentStep
     * 
     * @return int
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
    }

}
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

/**
 * CheckoutFinalizeEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutFinalizeEvent extends CheckoutEvent
{
    /** @var Response|null $response */
    protected $response;
    
    /** @var Request $request */
    protected $request;
    
    /** @var OrderInterface $order */
    protected $order;
    
    /** @var bool $completed */
    protected $completed = false;

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
    
    /**
     * getResponse
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * setResponse
     *
     * @param Response|null $response
     */
    public function setResponse(Response $response = null)
    {
        $this->response = $response;
        return $this;
    }
    
    /**
     * isComplete
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->completed;
    }
    
    /**
     * setComplete
     *
     * @param bool $complete
     */
    public function setComplete($completed)
    {
        $this->completed = $completed ? true : false;
        return $this;
    }
    
}
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
 * CheckoutProcessStepEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CheckoutProcessStepEvent extends CheckoutEvent
{
    /** @var Response|null $response */
    protected $response;
    
    /** @var Request $request */
    protected $request;
    
    /** @var Form $order */
    protected $form;
    
    /** @var bool $completed */
    protected $completed = false;

    /** @var bool $lastStep */
    protected $lastStep = false;
    
    /**
     * @param OrderInterface $order
     */
    public function __construct(FormInterface $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
        parent::__construct($form->getData());
    }
    
    /**
     * getForm
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
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
     * setResponse
     */
    public function setResponse(Response $response = null)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * setResponse
     */
    public function getResponse()
    {
        return $this->response;
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
    

    /**
     * isLastStep
     *
     * @return bool
     */
    public function isLastStep()
    {
        return $this->lastStep;
    }
    
    /**
     * setLastStep
     *
     * @param bool $lastStep
     */
    public function setLastStep($lastStep)
    {
        $this->lastStep = $lastStep ? true : false;
        return $this;
    }
}
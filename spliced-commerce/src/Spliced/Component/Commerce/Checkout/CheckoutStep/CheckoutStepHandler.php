<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Checkout\CheckoutStep;

use Spliced\Component\Commerce\Checkout\CheckoutStepHandlerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * CheckoutStepHandler
 * 
 * An abstract CheckoutStepHandler you can use to extend for other checkout
 * step handler for basic functionality.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class CheckoutStepHandler implements CheckoutStepHandlerInterface
{
    /**
     * @var int $step
     */
    protected $step = null;
    
    /**
     * {@inheritDoc}
     */
    public function getStep()
    {
        if(!isset($this->step) || is_null($this->step) || $this->step <= 0){
            throw new \Exception('Step Not Set, Empty, or Zero');
        }
        return $this->step;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setStep($step)
    {
        if(!is_numeric($step)|| $step <= 0){
            throw new \InvalidArgumentException('Argument 1 must be a numeric value greather than zero');
        }
        $this->step = (int) $step;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setPriority($priority)
    {
        $this->priority = (int) $priority;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function preBuildForm(OrderInterface $order)
    {
    
    }

    /**
     * {@inheritDoc}
     */
    public function buildFormOptions(OrderInterface $order, array $formOptions = array())
    {
        return array();
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(OrderInterface $order, FormBuilderInterface $builder)
    {
         
    }
    
    /**
     * {@inheritDoc}
     */
    public function process(FormInterface $form, Request $request)
    {
    
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderResponse()
    {
    
    }
}
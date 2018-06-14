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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Spliced\Component\Commerce\Model\OrderInterface;

/**
 * CheckoutStepHandlerInterface
 * 
 * All checkout steps must implement this interface. It assists in the
 * building of the checkout form for the given step, as well as handles the 
 * processing logic of the step during form submission.
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface CheckoutStepHandlerInterface
{
    
    /**
     * getStep
     * 
     * The step when this handler should be used
     * 
     * @return int
     */
    public function getStep();
    
    /**
     * setStep
     *
     * Set the step this handler should be used 
     * 
     */
    public function setStep($step);
    
    /**
     * getPriority
     *
     * Get the priority this step handler should be run
     *
     * @return int
     */
    public function getPriority();
    
    /**
     * setPriority
     *
     * Set the priority this step handler should be run
     *
    */
    public function setPriority($priority);
    
    /**
     * getName
     * 
     * A unique name representing this step in the checkout process
     * 
     * @return string
     */
    public function getName();    
    
    /**
     * getProgressBarLabel
     *
     * A label to show to the user in the checkout progress bar
     *
     * @return string
     */
    public function getProgressBarLabel();
    
    /**
     * preBuildForm
     * 
     * This method is called before any building of the checkout
     * form has taken place so you can hook in redirects, change steps
     * on certain situations, etc before the form is build and then
     * processed
     */
    public function preBuildForm(OrderInterface $order);
    
    /**
     * buildFormOptions
     *
     * Builds any options required by the form
     * 
     * @param array $formOptions
     * 
     * @return array
     */
    public function buildFormOptions(OrderInterface $order, array $formOptions = array());
    
    /**
     * buildForm
     *
     * Process upon the passed FormBuilderInterface for this step
     * 
     * @param FormBuilderInterface $builder
    */
    public function buildForm(OrderInterface $order, FormBuilderInterface $builder);

    /**
     * process
     * 
     * Processes the form data before and after submission.
     * 
     * If the step has been completed then this should return a
     * CheckoutMoveStepEvent, otherwise it should return a Response
     * 
     * @return CheckoutMoveStepEvent|Response
     */
    public function process(FormInterface $form, Request $request);

} 
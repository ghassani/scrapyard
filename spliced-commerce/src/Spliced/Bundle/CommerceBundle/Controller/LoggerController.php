<?php
/*
* This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * LoggerController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LoggerController extends Controller
{

    /**
     * @Template()
     * @Route("/logger/log/javascript-error", name="commerce_logger_log_javascript_error")
     * @Method({"POST"})
     *
     */
    public function logJavascriptErrorAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest() || !$this->getRequest()->request->has('message')){
            return new JsonResponse(array('success' => false, 'message' => 'Message Required or Invalid Request Type'));
        }
        
        $message = $this->getRequest()->request->get('message');
        
        $_details = array(
            'last_completed_checkout_step' => $this->get('commerce.checkout_manager')->getLastCompletedStep(),
            'current_checkout_step' => $this->get('commerce.checkout_manager')->getCurrentStep(),
            'current_order_id' => $this->get('commerce.checkout_manager')->getCurrentOrderId(),
        );
        
        $details = array();
        if($this->getRequest()->request->has('details')){
            $_details = array_merge($_details,$this->getRequest()->request->get('details'));
        }
        
        foreach($_details as $k => $v){
            $details[$k] = $k.': '.(is_array($v) ? implode(', ', $v) : $v);
        }
        
        $logMessage = sprintf('%s - Other Details: %s', $message, implode(', ', $details));
        
        $this->get('commerce.logger')->javascriptError($logMessage);
        
        return new JsonResponse(array('success' => true, 'message' => $logMessage));
    }

    /**
     * @Template()
     * @Route("/logger/log/javascript-notice", name="commerce_logger_log_javascript_notice")
     * @Method({"POST"})
     *
     */
    public function logJavascriptNoticeAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest() || !$this->getRequest()->request->has('message')){
            return new JsonResponse(array('success' => false, 'message' => 'Message Required or Invalid Request Type'));
        }
        
        $message = $this->getRequest()->request->get('message');
        
        $_details = array(
            'last_completed_checkout_step' => $this->get('commerce.checkout_manager')->getLastCompletedStep(),
            'current_checkout_step' => $this->get('commerce.checkout_manager')->getCurrentStep(),
            'current_order_id' => $this->get('commerce.checkout_manager')->getCurrentOrderId(),
        );
        
        $details = array();
        if($this->getRequest()->request->has('details')){
            $_details = array_merge($_details,$this->getRequest()->request->get('details'));
        }
        
        foreach($_details as $k => $v){
            $details[$k] = $k.': '.$v;
        }
        
        $logMessage = sprintf('%s - Other Details: %s', $message, implode(', ', $details));
        
        $this->get('commerce.logger')->javascriptNotice($logMessage);
        
        return new JsonResponse(array('success' => true, 'message' => $logMessage));
    }

}

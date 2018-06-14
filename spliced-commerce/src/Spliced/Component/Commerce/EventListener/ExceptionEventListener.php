<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace  Spliced\Component\Commerce\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Spliced\Component\Commerce\Event as Events;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\HttpKernel;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Logger\Logger;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * ExceptionEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ExceptionEventListener
{
    /**
     * 
     */
    public function __construct(ConfigurationManager $configurationManager, EngineInterface $templating, \Swift_Mailer $mailer, HttpKernelInterface $kernel, Logger $logger)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
        $this->mailer = $mailer;
        $this->configurationManager = $configurationManager;
        $this->logger = $logger;
    }
    
    /**
     * getLogger
     *
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }
      
    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    
    /**
     * getMailer
     *
     * @return Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }
    
    /**
     * getTemplating
     * 
     * @return TwigEngine
     */
    protected function getTemplating()
    {
        return $this->templating;
    }
   
    /**
     * getKernel
     * 
     * @return HttpKernelInterface
     */
    protected function getKernel()
    {
        return $this->kernel;
    }
    
    /**
     * onKernelException
     * 
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $emailOnException = $this->getConfigurationManager()->get('commerce.store.email_on_exception',0) == 1;
        $emailOn404Exception = $this->getConfigurationManager()->get('commerce.store.email_on_404',0) == 1;
        $environment = $this->getKernel()->getEnvironment();
        
        $response = new Response(null);
        
        if($environment == 'prod' || ($environment == 'dev' && $event->getRequest()->query->has('debug_error_view'))){
            if($exception instanceof NotFoundHttpException) {
                
                $response->setStatusCode(404)
                ->setContent($this->getTemplating()->render('SplicedCommerceBundle:Error:404.html.twig', array(
                    'exception' => $exception
                )));
                
            } else if($exception instanceof MethodNotAllowedHttpException){
                $response->setStatusCode(405)
                ->setContent($this->getTemplating()->render('SplicedCommerceBundle:Error:405.html.twig', array(
                    'exception' => $exception
                )));
            } else if($exception instanceof UnauthorizedHttpException){
                $response->setStatusCode(401)
                ->setContent($this->getTemplating()->render('SplicedCommerceBundle:Error:401.html.twig', array(
                    'exception' => $exception
                )));
            } else if($exception instanceof ServiceUnavailableHttpException){
                $response->setStatusCode(503)
                ->setContent($this->getTemplating()->render('SplicedCommerceBundle:Error:503.html.twig', array(
                    'exception' => $exception
                )));
            } else {
                $response->setStatusCode(503)
                ->setContent($this->getTemplating()->render('SplicedCommerceBundle:Error:503.html.twig', array(
                    'exception' => $exception
                )));
            }
        }

        if(!$exception instanceof NotFoundHttpException) {
            $this->getLogger()->exception(sprintf('Uncaught Exception: %s - %s', get_class($exception), $exception->getMessage()));
        }


        if(($emailOnException || $emailOn404Exception) && $environment == 'prod'){
            $notificationMessage = \Swift_Message::newInstance()
              ->setFrom($this->getConfigurationManager()->get('commerce.store.email.webmaster'))
              ->setTo($this->getConfigurationManager()->get('commerce.store.email.webmaster'))
              ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));
            
            if($exception instanceof NotFoundHttpException) {
                
                $this->getLogger()->pageNotFound($exception->getMessage()); 
                
                if($emailOn404Exception){
                    $notificationMessage->setSubject($this->getConfigurationManager()->get('commerce.store.name').' - 404 Not Found')
                    ->setBody($this->getTemplating()->render('SplicedCommerceBundle:Email:404.html.twig', array(
                        'exception' => $exception,
                    )), 'text/html');
                        
                    $this->getMailer()->send($notificationMessage);
                } 
                
            } else {
                
                $this->getLogger()->exception($exception->getMessage());
                
                $notificationMessage->setSubject($this->getConfigurationManager()->get('commerce.store.name').' - Website Exception')
                ->setBody($this->getTemplating()->render('SplicedCommerceBundle:Email:exception.html.twig', array(
                  'exception' => $exception,
                  'class' => get_class($exception),
                )), 'text/html');
                
                $this->getMailer()->send($notificationMessage);
            }            
        }
                
        if($response->getContent()){
            $event->stopPropagation();
            $event->setResponse($response);
        }
    }
    
    
    /**
     * onAdminKernelException
     * 
     * @param GetResponseForExceptionEvent
     */
    public function onAdminKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        
        $notificationMessage = \Swift_Message::newInstance()
          ->setFrom($this->getConfigurationManager()->get('commerce.store.email.noreply'))
          ->setTo($this->getConfigurationManager()->get('commerce.store.email.webmaster'))
          ->setSubject('Admin Exception')
          ->setBody($exception->getMessage().PHP_EOL.$exception->getTraceAsString());
         
        $this->getMailer()->send($notificationMessage);
    }
}
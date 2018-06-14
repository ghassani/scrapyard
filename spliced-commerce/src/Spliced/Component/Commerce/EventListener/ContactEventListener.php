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
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Spliced\Component\Commerce\Model;


/**
 * ContactEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContactEventListener
{
    /**
     * 
     */
    public function __construct(ConfigurationManager $configurationManager, EntityManager $em, SecurityContext $securityContext, \Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->configurationManager = $configurationManager;
        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->mailer = $mailer;
        $this->templating = $templating;
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
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getManager
     *
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->em;
    }
    
    /**
     * onContactFormSubmission
     * 
     * @param ContactFormSubmissionEvent $event
     */
    public function onContactFormSubmission(Events\ContactFormSubmissionEvent $event)
    {
         $contactMessage = $event->getContactMessage();

         // send email
         $notificationMessage = \Swift_Message::newInstance()
         ->setSubject($this->getConfigurationManager()->get('commerce.store.name').' - Contact Form Submission - '.$contactMessage->getSubject())
         ->setFrom($contactMessage->getEmail())
         ->setTo($this->getConfigurationManager()->get('commerce.store.email.contact'))
         ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.contact_notification', 'SplicedCommerceBundle:Email:contact_notification.html.twig'), array(
             'submission' => $contactMessage,
         )), 'text/html')
         ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.contact_bounced'));
         
         $this->getMailer()->send($notificationMessage);
         
         $this->em->persist($contactMessage);
         $this->em->flush($contactMessage);
    }
}
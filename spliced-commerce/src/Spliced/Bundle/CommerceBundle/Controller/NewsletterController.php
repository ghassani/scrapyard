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
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceBundle\Form\Type\NewsletterSubscribeFormType;
use Spliced\Bundle\CommerceBundle\Form\Type\NewsletterUnsubscribeFormType;
use Spliced\Bundle\CommerceBundle\Entity\NewsletterSubscriber;

/**
 * NewsletterController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class NewsletterController extends Controller
{
    
    /**
     * @Route("/newsletter", name="commerce_newsletter_subscribe")
     * @Template("SplicedCommerceBundle:Newsletter:subscribe.html.twig")
     */
    public function subscribeAction()
    {
        
        $newsletterSubscriber = new NewsletterSubscriber();
        
        if($this->get('security.context')->isGranted('ROLE_USER')){
            $newsletterSubscriber->setEmail($this->get('security.context')->getToken()->getUser()->getEmail());
        }
        
        $form = $this->createForm(new NewsletterSubscribeFormType(), $newsletterSubscriber);
        
        if($this->getRequest()->isMethod('POST')) {
            
            if($form->bind($this->getRequest()) && $form->isValid()) {
                
                $newsletterSubscriber = $form->getData();
                
                try{
                    $existingSubscriber = $this->getDoctrine()->getRepository('SplicedCommerceBundle:NewsletterSubscriber')
                  ->findOneByEmail($newsletterSubscriber->getEmail());
                }catch(NoResultException $e) {
                    $this->getDoctrine()->getManager()->persist($newsletterSubscriber);
                    $this->getDoctrine()->getManager()->flush();
                    
                    $this->get('session')->getFlashBag()->add('success', 'You have been successfully subscribed.');
                    return $this->redirect($this->generateUrl('homepage'));
                }
                
                $this->get('session')->getFlashBag()->add('error', 'You are already subscribed.');
                                
                
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Your E-Mail is Invalid');
            }
        }
        
        return array('form' => $form->createView());
    }
    
    /**
     * @Route("/newsletter/unsubscribe", name="commerce_newsletter_unsubscribe")
     * @Template("SplicedCommerceBundle:Newsletter:unsubscribe.html.twig")
     */
    public function unsubscribeAction()
    {
        $newsletterSubscriber = new NewsletterSubscriber();
        
        if($this->get('security.context')->isGranted('ROLE_USER')){
            $newsletterSubscriber->setEmail($this->get('security.context')->getToken()->getUser()->getEmail());
        }
         
        $form = $this->createForm(new NewsletterUnsubscribeFormType(), $newsletterSubscriber);
        
        if($this->getRequest()->isMethod('POST')) {
        
            if($form->bind($this->getRequest()) && $form->isValid()) {
        
                $newsletterSubscriber = $form->getData();
        
                $existingSubscriber = $this->getDoctrine()
                  ->getRepository('SplicedCommerceBundle:NewsletterSubscriber')
                  ->findOneByEmail($newsletterSubscriber->getEmail());
        
                if($existingSubscriber) {
        
                    $this->getDoctrine()->getManager()->remove($existingSubscriber);
                    $this->getDoctrine()->getManager()->flush();
        
                    $this->get('session')->getFlashBag()->add('success', 'You have been successfully unsubscribed.');
                    return $this->redirect($this->generateUrl('homepage'));
                } else {
                    $this->get('session')->getFlashBag()->add('error', 'You are not subscribed to be able to unsubscribe.');
                }
        
        
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Your E-Mail is Invalid');
            }
        }
        
        return array('form' => $form->createView());
    }
    
}
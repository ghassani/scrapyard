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
use Spliced\Bundle\CommerceBundle\Form\Type\ContactFormType;
use Spliced\Bundle\CommerceBundle\Entity\ContactMessage;
use Spliced\Component\Commerce\Event as Events;
/**
 * ContactController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ContactController extends Controller
{
    /**
     * @Template("SplicedCommerceBundle:Contact:index.html.twig")
     * @Route("/contact", name="commerce_contact")
     */
    public function indexAction()
    {

        $contactFormObject = new ContactMessage();
        
        if($this->get('security.context')->isGranted('ROLE_USER')){
            $customer = $this->get('security.context')->getToken()->getUser();

            $contactFormObject->setName($customer->getProfile()->getFullName())
              ->setEmail($customer->getEmail())
              ->setCustomer($customer);
        }
        
        $form = $this->createForm(new ContactFormType(), $contactFormObject);
        
        if($this->getRequest()->getMethod() == 'POST') {
            if($form->bind($this->getRequest()) && $form->isValid()) {
                
                $contactMessage = $form->getData();

                $this->get('event_dispatcher')->dispatch(
                    Events\Event::EVENT_CONTACT_SUBMISION, 
                    new Events\ContactFormSubmissionEvent($contactMessage)
                );
                
                $this->get('session')->getFlashBag()->add('success', 'Thank you for contacting us. We will get back with you shortly.');
                return $this->redirect($this->generateUrl('homepage'));
                
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Your submission was invalid.');
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}

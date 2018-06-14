<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Component\Commerce\HttpFoundation\AjaxResponse;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\LoginFormType;
use Symfony\Component\Security\Core\SecurityContext;
use Spliced\Component\Commerce\Event as Events;
use Doctrine\ORM\NoResultException;

/**
 * SecurityController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class SecurityController extends Controller
{
    /**
     * @Template("SplicedCommerceAdminBundle:Security:login.html.twig")
     * @Route("/login", name="commerce_admin_login")
     */
    public function loginAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('commerce_dashboard'));
        }

        $session     = $this->get('session');
        $userManager = $this->get('commerce.user.manager');
        $dispatcher  = $this->get('event_dispatcher');
        $request     = $this->get('request');
        $referer     = $request->headers->get('referer');
        
        $form = $this->createForm(new LoginFormType());
        
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        if(isset($error) && $error instanceof \Exception){
            $error = $error->getMessage();
        }

        /*
        if ($this->container->get('request')->isXmlHttpRequest()) {
            return new AjaxResponse(array(
                'success' => true,
                'message' => 'Login',
                'modal' => $this->container->get('templating')->renderResponse('SplicedCommerceBundle:Common:modal.html.twig',array(
                    'title' => 'Login',
                    'body' => $this->get('templating')->renderResponse('SplicedCommerceBundle:Security:login_register_ajax.html.twig', array(
                        'error'  => isset($error) ? $error : null,
                        'loginForm' => $form->createView(),
                        'registrationForm' => $registrationForm->createView(),
                    ))->getContent()
                ))->getContent()
            ));
        }
        */
        return array(
            'error' => isset($error) ? $error : null,
            'form' => $form->createView(),
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
    
    /**
     * {@inheritDoc}
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
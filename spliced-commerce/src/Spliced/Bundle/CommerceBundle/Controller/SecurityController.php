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
use Symfony\Component\HttpFoundation\JsonResponse;
use Spliced\Bundle\CommerceBundle\Form\Type\RegistrationFormType;
use Spliced\Bundle\CommerceBundle\Form\Type\LoginFormType;
use Spliced\Bundle\CommerceBundle\Form\Type\PasswordResetFormType;
use Spliced\Bundle\CommerceBundle\Form\Type\ForgotPasswordFormType;
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
     * @Template("SplicedCommerceBundle:Security:login_register.html.twig")
     * @Route("/account/login", name="commerce_login")
     * @Route("/account/register", name="commerce_register")
     */
    public function loginAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            // already logged in
            return $this->redirect($this->generateUrl('account'));
        }

        $session     = $this->get('session');
        $customerManager = $this->get('commerce.customer.manager');
        $dispatcher  = $this->get('event_dispatcher');
        $request     = $this->get('request');
        $referer     = $request->headers->get('referrer');
        
        $loginForm = $this->createForm(new LoginFormType());
        $registrationFormType = new RegistrationFormType();
        $registrationForm = $this->createForm($registrationFormType);

        if ($request->isMethod('POST') && $request->request->has($registrationFormType->getName())) {
            // handle registration form submission
            if ($registrationForm->bind($request) && $registrationForm->isValid()) {
                
                $newCustomer = $registrationForm->getData();

                try {
                    $existingCustomer = $customerManager->loadUserByEmail($newCustomer->getEmail());
                    
                    $session->getFlashBag()->add('error','It appears that you already have an account with us. Login below or request a password change.');

                    return $this->redirect($this->generateUrl('commerce_login'));
                    
                } catch (NoResultException $e) {
                    
                    // doesnt exist, lets save customer using the customer
                    // manager to fire related events in the dispatcher
                    $customerManager->create($newCustomer);                    
                  
                    // lets login the user now
                    $this->get('commerce.security.login_manager')
                      ->loginUser('main', $newCustomer, null);
                    
                    return $this->redirect(!empty($referer) ? $referer : $this->generateUrl('commerce_account'));
                }
            }
        } else {
            $dispatcher->dispatch(
                Events\Event::EVENT_CUSTOMER_REGISTRATION_START, 
                new Events\RegistrationStartEvent()
            );
        }

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
                
        if ($this->get('request')->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Login',
                'modal' => $this->get('templating')->renderResponse('SplicedCommerceBundle:Common:modal.html.twig',array(
                    'title' => 'Login',
                    'body' => $this->get('templating')->renderResponse('SplicedCommerceBundle:Security:login_register_ajax.html.twig', array(
                        'error'  => isset($error) ? $error : null,
                        'loginForm' => $loginForm->createView(),
                        'registrationForm' => $registrationForm->createView(),
                    ))->getContent()
                ))->getContent()
            ));
        }

        return array(
            'error' => isset($error) ? $error : null,
            'loginForm' => $loginForm->createView(),
            'registrationForm' => $registrationForm->createView(),
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Security/Form:login.html.twig")
     */
    public function loginBlockAction($withLabels = true)
    {
        $session = $this->get('session');
        $userManager = $this->get('commerce.customer.manager');
        $dispatcher = $this->get('event_dispatcher');
        $request = $this->get('request');

        $form = $this->createForm(new LoginFormType());

        return array(
            'withLabels' => $withLabels,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Security/Form:register.html.twig")
     */
    public function registerBlockAction()
    {
        $form = $this->createForm(new RegistrationFormType());

        $this->get('event_dispatcher')->dispatch(Events\SecurityEvent::EVENT_SECURITY_REGISTRATION_START, new Events\RegistrationStartEvent());

        return array(
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

    /**
     * @Template("SplicedCommerceBundle:Security:forgot_password.html.twig")
     * @Route("/login/forgot-password", name="commerce_forgot_password")
     */
    public function forgotPasswordAction()
    {
        $form = $this->createForm(new ForgotPasswordFormType());
        
        if($this->getRequest()->getMethod() == 'POST'){
            if($form->bind($this->getRequest()) && $form->isValid()){
                $formData = $form->getData();
                try{
                    $user = $this->getDoctrine()->getManager()->getRepository('SplicedCommerceBundle:Customer')
                      ->findOneByEmail($formData['email']);
                    
                    $this->get('event_dispatcher')->dispatch(
                        Events\Event::EVENT_SECURITY_LOGIN_PASSWORD_RESET_REQUEST,
                        new Events\PasswordResetRequestEvent($user)
                    );
                    
                    $this->get('session')->getFlashBag()->add('success',sprintf('An email has been sent to %s with further instructions.', $user->getEmail()));
                    return $this->redirect($this->generateUrl('login'));
                } catch(NoResultException $e){
                    $this->get('session')->getFlashBag()->add('error','User does not exist');
                }
            }
        }
        return array(
            'form' => $form->createView()
        );
    }
    
    /**
     * @Template("SplicedCommerceBundle:Security:password_reset.html.twig")
     * @Route("/login/password-reset/{userId}/{confirmationToken}", name="commerce_password_reset")
     */
    public function passwordResetAction($userId, $confirmationToken)
    {
        try{
            $user = $this->getDoctrine()->getManager()->getRepository('SplicedCommerceBundle:Customer')
            ->findOneById($userId);
        } catch(NoResultException $e){
            $this->get('session')->getFlashBag()->add('error', 'User does not exist');
            return $this->redirect($this->generateUrl('login'));
        }
    
        if($user->getConfirmationToken() != $confirmationToken){
            $this->get('session')->getFlashBag()->add('error', 'Security Token does not match or has expired');
            return $this->redirect($this->generateUrl('login'));
        }
    
        $form = $this->createForm(new PasswordResetFormType());
    
        if($this->getRequest()->getMethod() == 'POST'){
            if($form->bind($this->getRequest()) && $form->isValid()){
                $formData = $form->getData();
    
                $this->get('event_dispatcher')->dispatch(
                    Events\Event::EVENT_SECURITY_LOGIN_PASSWORD_RESET,
                    new Events\PasswordResetEvent($user, $formData['resetPassword'])
                );
    
                $this->get('session')->getFlashBag()->add('success', 'Your password has been successfully updated. You may now login below.');
                return $this->redirect($this->generateUrl('login'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Looks like your password is invalid');
            }
        }
    
        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }
    
    /**
     * @Template("SplicedCommerceBundle:Security:force_password_reset.html.twig")
     * @Route("/login/force-password-reset/{userId}/{confirmationToken}", name="commerce_force_password_reset")
     */
    public function forcePasswordResetAction($userId,$confirmationToken)
    {
        try{
            $user = $this->getDoctrine()->getManager()->getRepository('SplicedCommerceBundle:Customer')
              ->findOneById($userId);
        } catch(NoResultException $e){
            $this->get('session')->getFlashBag()->add('error', 'User does not exist');
            return $this->redirect($this->generateUrl('login'));
        }
        
        if($user->getConfirmationToken() != $confirmationToken){    
            $this->get('session')->getFlashBag()->add('error', 'Security Token does not match or has expired');
            return $this->redirect($this->generateUrl('login'));
        }
        
        $form = $this->createForm(new PasswordResetFormType());
        
        if($this->getRequest()->getMethod() == 'POST'){
            if($form->bind($this->getRequest()) && $form->isValid()){
                $formData = $form->getData();
                
                $this->get('event_dispatcher')->dispatch(
                    Events\Event::EVENT_SECURITY_LOGIN_FORCE_PASSWORD_RESET,
                    new Events\ForcePasswordResetEvent($user, $formData['resetPassword'])
                );

                $this->get('session')->getFlashBag()->add('success', 'Your password has been successfully updated. You may now login below.');
                return $this->redirect($this->generateUrl('login'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Looks like your password is invalid');
            }
        }
        
        return array(
          'user' => $user, 
          'form' => $form->createView()     
        );
    }
    
}
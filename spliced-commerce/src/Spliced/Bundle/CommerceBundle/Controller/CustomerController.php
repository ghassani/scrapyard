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
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Spliced\Bundle\CommerceBundle\Form\Type\CustomerAddressFormType;
use Spliced\Bundle\CommerceBundle\Form\Type\CustomerProfileFormType;
use Spliced\Bundle\CommerceBundle\Entity\NewsletterSubscriber;
use Spliced\Bundle\CommerceBundle\Form\Type\PasswordResetFormType;
use Spliced\Bundle\CommerceBundle\Form\Type\FinalizeRegistrationFormType;
use Spliced\Component\Commerce\Model\CustomerInterface;
use Doctrine\ORM\NoResultException;

/**
 * CustomerController
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerController extends Controller
{
    /**
     * @Template("SplicedCommerceBundle:Customer:dashboard.html.twig")
     * @Route("/account", name="commerce_account")
     * @Secure(roles="ROLE_USER")
     *
     * Account
     *
     */
    public function accountAction()
    {

        try {
            $customer = $this->get('doctrine.orm.entity_manager')->getRepository("SplicedCommerceBundle:Customer")
              ->findOneForView($this->get('security.context')->getToken()->getUser());
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $recentOrders = $this->get('doctrine.orm.entity_manager')
            ->getRepository("SplicedCommerceBundle:Order")
            ->getRecentOrdersForCustomer($customer);
        
        return array(
            'recentOrders' => $recentOrders,
            'customer' => $customer,
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Customer:profile.html.twig")
     * @Route("/account/change-password", name="commerce_account_change_password")
     * @Secure(roles="ROLE_USER")
     * @Method("POST")
     *
     * Change User Password
     *
     */
    public function changePasswordAction()
    {
        try {
            $customer = $this->get('doctrine.orm.entity_manager')->getRepository("SplicedCommerceBundle:Customer")
            ->findOneForView($this->get('security.context')->getToken()->getUser());
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        $resetPasswordForm = $this->createForm(new PasswordResetFormType());
        
        if($resetPasswordForm->bind($this->getRequest()) && $resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            
            $customer->setPlainPassword($formData['resetPassword']);
            $this->get('commerce.customer.manager')->updatePassword($customer);
            
            $this->getDoctrine()->getManager()->persist($customer);
            $this->getDoctrine()->getManager()->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Your password has been updated!');
            return $this->redirect($this->generateUrl('account'));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your information');
        
        return array(
            'customer' => $customer,
            'profileForm' => $this->createForm(new CustomerProfileFormType(), $customer->getProfile())->createView(),
            'resetPasswordForm' => $resetPasswordForm->createView(),
        );
    }


    /**
     * @Template("SplicedCommerceBundle:Customer:profile.html.twig")
     * @Route("/account/profile", name="commerce_account_profile")
     * @Secure(roles="ROLE_USER")
     *
     * Account Profile
     *
     */
    public function profileAction()
    {

        try {
            $customer = $this->get('doctrine.orm.entity_manager')->getRepository("SplicedCommerceBundle:Customer")
            ->findOneForView($this->get('security.context')->getToken()->getUser());
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        return array(
            'customer' => $customer,
            'profileForm' => $this->createForm(new CustomerProfileFormType(), $customer->getProfile())->createView(),
            'resetPasswordForm' => $this->createForm(new PasswordResetFormType())->createView(),
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Customer:profile.html.twig")
     * @Route("/account/profile/update", name="commerce_account_profile_update")
     * @Secure(roles="ROLE_USER")
     * @Method("POST")
     *
     * Update Account Profile
     *
     */
    public function profileUpdateAction()
    {
    
        try {
            
            $customer = $this->get('doctrine.orm.entity_manager')
            ->getRepository("SplicedCommerceBundle:Customer")
            ->findOneForView($this->get('security.context')->getToken()->getUser());
            
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        $profileForm = $this->createForm(new CustomerProfileFormType(), $customer->getProfile());
        
        if($profileForm->bind($this->getRequest()) && $profileForm->isValid()) {
            $profile = $profileForm->getData();
            
            $this->getDoctrine()->getManager()->persist($profile);
            
            
            try{
                $newsletterSubscriber = $this->get('doctrine.orm.entity_manager')
                ->getRepository("SplicedCommerceBundle:NewsletterSubscriber")
                ->findOneByEmail($customer->getEmail());
                
                if(!$profile->getNewsletterSubscriber()) {
                    $this->getDoctrine()->getManager()->remove($newsletterSubscriber);
                }
                
            } catch(NoResultException $e) {
                if($profile->getNewsletterSubscriber()) {
                    $newsletterSubscriber = new NewsletterSubscriber();
                    $newsletterSubscriber->setEmail($customer->getEmail());
                    $this->getDoctrine()->getManager()->persist($newsletterSubscriber);
                }
            }
            
            $this->getDoctrine()->getManager()->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Your profile has been updated!');
            return $this->redirect($this->generateUrl('account'));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your information');
        
        return array(
            'customer' => $customer,
            'profileForm' => $profileForm->createView(),
            'resetPasswordForm' => $this->createForm(new PasswordResetFormType())->createView(),
        );
    }
    
    /**
     * @Template("SplicedCommerceBundle:Customer:orders.html.twig")
     * @Route("/account/orders", name="commerce_account_orders")
     * @Secure(roles="ROLE_USER")
     *
     * Orders
     *
     */
    public function ordersAction()
    {
        $orderRepository = $this->getDoctrine()
          ->getManager()
          ->getRepository('SplicedCommerceBundle:Order');
        
        
        $customerRepository = $this->getDoctrine()
          ->getManager()
          ->getRepository('SplicedCommerceBundle:Customer');
        
        try {
        
            $customer = $customerRepository->findOneForView(
                $this->get('security.context')->getToken()->getUser()
            );
        
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('commerce_account'));
        }
        
        // load orders, paginated
        $orders = $this->get('knp_paginator')->paginate(
            $orderRepository->getOrdersForCustomerListQuery($customer),
            $this->getRequest()->query->get('page',1),
            $this->get('commerce.product.filter_manager')->getPerPage()
        );
        
        return array(
            'customer' => $customer,
            'orders' => $orders,  
        );
    }

    /**
     * @Template("SplicedCommerceBundle:Customer:address_book.html.twig")
     * @Route("/account/address-book", name="commerce_account_address_book")
     * @Secure(roles="ROLE_USER")
     *
     * Address Book
     *
     */
    public function addressBookAction()
    {
        try {
            $customer = $this->get('doctrine.orm.entity_manager')->getRepository("SplicedCommerceBundle:Customer")
            ->findOneForView($this->get('security.context')->getToken()->getUser());
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('commerce_account'));
        }
        
        return array(
            'customer' => $customer,
        );
    }

    /**
     * @Route("/account/set-preferred-address/{type}/{id}", name="commerce_account_addresses_set_prefered")
     * @Secure(roles="ROLE_USER")
     *
     * Addressess
     *
     */
    public function setPreferredAddressAction($type, $id)
    {
        $type = $type == 'shipping' ? 'shipping' : 'billing';
        $customer = $this->get('security.context')->getToken()->getUser();

        try {
            $address = $this->get('doctrine.orm.entity_manager')->getRepository('SplicedCommerceBundle:CustomerAddress')
              ->findAddressByIdForCustomer($customer, $id);

        } catch (NoResultException $e) {
            $this->get('session')->getFlashBag()->add('error', 'Address Not Found');

            return $this->redirect($this->generateUrl('account'));
        }

        $customerProfile = $customer->getProfile();

        if ($type === 'shipping') {
            $customerProfile->setPreferedShippingAddress($address);
        } else {
            $customerProfile->setPreferedBillingAddress($address);
        }

        $this->get('doctrine.orm.entity_manager')->persist($customerProfile);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('session')->getFlashBag()->add('success', sprintf('Preferd %s address updated',$type));

        return $this->redirect($this->generateUrl('account'));
    }

    /**
     * @Route("/account/delete-address/{id}", name="commerce_account_addresses_delete")
     * @Secure(roles="ROLE_USER")
     *
     * Delete Address
     *
     */
    public function deleteAddressAction($id)
    {

        $customer = $this->get('security.context')->getToken()->getUser();

        try {
            $address = $this->get('doctrine.orm.entity_manager')->getRepository('SplicedCommerceBundle:CustomerAddress')
            ->findAddressByIdForCustomer($customer, $id);

        } catch (NoResultException $e) {
            $this->get('session')->getFlashBag()->add('error', 'Address Not Found');

            return $this->redirect($this->generateUrl('account'));
        }

        $this->get('doctrine.orm.entity_manager')->remove($address);
        $this->get('doctrine.orm.entity_manager')->flush();

        $this->get('session')->getFlashBag()->add('success', 'Address successfully deleted');

        return $this->redirect($this->generateUrl('account'));
    }

    /**
     * @Route("/account/add-address", name="commerce_account_addresses_add")
     * @Secure(roles="ROLE_USER")
     * @Template("SplicedCommerceBundle:Customer:add_address.html.twig")
     *
     * Add Address
     *
     */
    public function addAddressAction()
    {
        try {
            $customer = $this->getDoctrine()->getManager()->getRepository("SplicedCommerceBundle:Customer")
            ->findOneForView($this->get('security.context')->getToken()->getUser());
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createForm(new CustomerAddressFormType());

        if ($this->getRequest()->getMethod() == 'POST' ) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {

                $address = $form->getData();
                $address->setCustomer($customer);

                $this->getDoctrine()->getManager()->persist($address);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'Address successfully added to your account.');

                return $this->redirect($this->generateUrl('commerce_account_address_book'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your submission.');
            }
        }

        return array(
            'form' => $form->createView(),
            'customer' => $customer,
        );
    }

    /**
     * @Route("/account/edit-address/{id}", name="commerce_account_addresses_edit")
     * @Secure(roles="ROLE_USER")
     * @Template("SplicedCommerceBundle:Customer:edit_address.html.twig")
     *
     * Edit Address
     *
     */
    public function editAddressAction($id)
    {
        try {
            $customer = $this->getDoctrine()->getManager()->getRepository("SplicedCommerceBundle:Customer")
            ->findOneForView($this->get('security.context')->getToken()->getUser());
            
            $address = $this->get('doctrine.orm.entity_manager')->getRepository('SplicedCommerceBundle:CustomerAddress')
            ->findAddressByIdForCustomer($customer, $id); 
            
        } catch (NoResultException $e) {
            return $this->redirect($this->generateUrl('commerce_account_address_book'));
        }
    
        $form = $this->createForm(new CustomerAddressFormType(), $address);
    
        if ($this->getRequest()->getMethod() == 'POST' ) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
    
                $address = $form->getData();
                $address->setCustomer($customer);
    
                $this->getDoctrine()->getManager()->persist($address);
                $this->getDoctrine()->getManager()->flush();
    
                $this->get('session')->getFlashBag()->add('success', 'Address successfully updated.');
    
                return $this->redirect($this->generateUrl('commerce_account_address_book'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your submission.');
            }
        }
    
        return array(
            'form' => $form->createView(),
            'customer' => $customer,
            'address' => $address,
        );
    }
    
    
    /**
     * @Route("/account/get-address/{id}", name="commerce_account_addresses_get")
     * @Secure(roles="ROLE_USER")
     *
     * Get Address
     *
     */
    public function getAddressAction($id)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNowFoundException('Address Not Found');
        }

        try {

            $address = $this->get('doctrine.orm.entity_manager')->getRepository('SplicedCommerceBundle:CustomerAddress')
            ->findAddressByIdForCustomer($this->get('security.context')->getToken()->getUser(), $id);

        } catch (NoResultException $e) {
            throw $this->createNowFoundException('Address Not Found');
        }

        return new JsonResponse(array(
            'success' => true,
            'label' => $address->getAddressLabel(),
            'first_name' => $address->getFirstName(),
            'last_name' => $address->getLastName(),
            'attn' => $address->getAttn(),
            'address' => $address->getAddress(),
            'address2' => $address->getAddress2(),
            'city' => $address->getCity(),
            'state' => $address->getState(),
            'zipcode' => $address->getZipcode(),
            'country' => $address->getCountry(),
            'phoneNumber' => $address->getPhoneNumber(),
        ));
    }

    /**
     * @Template("SplicedCommerceBundle:Customer:finalize_registration.html.twig")
     * @Route("/account/finalize-registration", name="commerce_account_finalize_registration")
     * @Secure(roles="ROLE_USER")
     *
     * Account
     *
     */
    public function finalizeRegistrationAction()
    {
        $customer = $this->get('security.context')->getToken()->getUser();
        $originalEmail = $customer->getEmail();
        
        if(!$customer->getForcePasswordReset() && !$customer->getForceCollectEmail()){
            return $this->redirect($this->generateUrl('commerce_account'));
        }
        
        if($customer->getForceCollectEmail()){
            $customer->setEmail(null);
        }
                
        $form = $this->createForm(new FinalizeRegistrationFormType($customer), $customer);
        
        if($this->getRequest()->getMethod() == 'POST' && 
            $this->getRequest()->request->has('finalize_registration')){
            
            if($form->bind($this->getRequest()) && $form->isValid()) {
                
                $user = $form->getData();
                // lets look for an existing email by the submitted email
                try{
                    $searchedUser = $this->getDoctrine()->getRepository('SplicedCommerceBundle:Customer')
                      ->findOneByEmail($user->getEmail()); 

                } catch(NoResultException $e){
                    // do nothing here, continue as normal
                }
                
                if(isset($searchedUser) && $searchedUser instanceof CustomerInterface && $user->getId() != $searchedUser->getId()) {
                    $this->get('session')->getFlashBag()->add('error',
                        sprintf('Looks like the email address you provided is already registered. <a href="%s" title="Recover your password">
                                Did you forget your password?</a>', $this->generateUrl('commerce_forgot_password'))
                    );
                    $user->setEmail($originalEmail);
                } else {
                    $this->get('commerce.customer.manager')->updatePassword($user);
                
                    $user->setForceCollectEmail(false)
                    ->setForcePasswordReset(false);
                
                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush();
                
                    $this->get('session')->getFlashBag()->add('success', 'Your account has been updated. Your account registration has been completed and is now ready for use.');
                    return $this->redirect($this->generateUrl('homepage'));
                }               
                                
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Whoops, looks like something is invalid.');
                $customer->setEmail($originalEmail);
            }            
        }
        
        return array(
            'customer' => $customer,
            'form' => $form->createView(),
        );
    }
    
}

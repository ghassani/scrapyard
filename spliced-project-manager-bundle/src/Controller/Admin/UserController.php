<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Helper;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;
use Spliced\Bundle\ProjectManagerBundle\Entity;
use Spliced\Bundle\ProjectManagerBundle\HttpFoundation\AjaxJsonResponse;

class UserController extends Controller
{
    /**
     * @Route("/users", name="spliced_pms_user_list")
     * @Template()
     */
    public function listAction()
    {
		$query = $this->getDoctrine()->getRepository($this->container->getParameter('spliced_project_manager.user_class'))
		  ->createQueryBuilder('u');
		
		$pagination = $this->get('knp_paginator')->paginate(
		    $query,
		    $this->get('request')->query->get('page', 1)/*page number*/,
		    20/*limit per page*/
		);
		
		$listConfiguration = new Helper\ListConfigurationLoader(dirname(__FILE__).'/../Resources/config/list/client.yml');

        return array('pagination' => $pagination, 'listConfiguration' => $listConfiguration);
    }
    
    /**
     * @Route("/users/new", name="spliced_pms_user_new")
     * @Template()
     */
    public function newAction(){
    	$form = $this->createForm(new Forms\UserFormType());
    	
    	if($this->getRequest()->isXmlHttpRequest()){
	    	$response = new AjaxJsonResponse(null,array('reset' => false));
	    	$response->setData('success', true)
			->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
				'title' => 'Creating New User',
				'class' => 'container',
				'buttons' => array(
					array(
						'id' => 'btn-user-save-close',
						'class' => '',
						'label' => 'Cancel',
						'dismiss' => true,
					),
					array(
						'id' => 'btn-user-save',
						'class' => 'btn-primary',
						'label' => 'Save',
						'dismiss' => false,
					)	
				),
				'body' => $this->render('SplicedProjectManagerBundle:User:ajax_form.html.twig',array(
					'form' => $form->createView()
				))->getContent(),
			))->getContent(), true);
			return $response;
    	}
    	
    	return array(
    		'form' => $form->createView(),
    	);
    }

    /**
     * @Route("/users/edit/{id}", name="spliced_pms_user_edit")
     * @Template()
     */
    public function editAction($id){

    }
    /**
     * @Route("/users/delete/{id}", name="spliced_pms_user_delete")
     * @Template()
     */
    public function deleteAction($id){

    }

    /**
     * @Route("/users/save", name="spliced_pms_user_save")
     * @Template("SplicedProjectManagerBundle:User:new.html.twig")
     * @Method({"POST"})
     */
    public function saveAction(){
    	$form = $this->createForm(new Forms\UserFormType());
    	$response = new AjaxJsonResponse(null,array('reset' => false));
    	$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
    		'title' => 'Whoops',
    		'body' => '<p>There was an error processing the request.</p>'
    	))->getContent(), true);
    	
    	if($form->bindRequest($this->getRequest()) && $form->isValid()){
    		$user = $form->getData();
    		
    		if($this->getRequest()->isXmlHttpRequest()){
	    		$response->setData('success', true)
	    		->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
	    			'title' => 'Whoops',
	    			'body' => '<p>User successfully added.</p>'
	    		))->getContent(), true);
    		}
    		
    	} else {
    		$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
    			'title' => 'Whoops',
    			'body' => '<p>Form did not pass validation.</p>'
    		))->getContent(), true);
    	}
    	    	
    	return $this->getRequest()->isXmlHttpRequest() ? $response : array(
    		'form' => $form->createView(),
    	);
    }
}

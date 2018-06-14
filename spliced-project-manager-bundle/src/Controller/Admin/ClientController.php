<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Helper;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;
use Spliced\Bundle\ProjectManagerBundle\Entity;

class ClientController extends Controller
{
    /**
     * @Route("/clients", name="spliced_pms_client_list")
     * @Template()
     */
    public function listAction()
    {
		$query = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Client')
		  ->createQueryBuilder('c');
		
		$pagination = $this->get('knp_paginator')->paginate(
		    $query,
		    $this->get('request')->query->get('page', 1)/*page number*/,
		    20/*limit per page*/
		);
		
		$listConfiguration = new Helper\ListConfigurationLoader(dirname(__FILE__).'/../Resources/config/list/client.yml');

        return array('pagination' => $pagination, 'listConfiguration' => $listConfiguration);
    }
    
    /**
     * @Route("/clients/new", name="spliced_pms_client_new")
     * @Template()
     */
    public function newAction(){

        $form = $this->createForm(new Forms\ClientFormType(true));

    	return array(
    		'form' => $form->createView()
    	);
    }
    
    /**
     * @Route("/clients/save", name="spliced_pms_client_save")
     * @Template("SplicedProjectManagerBundle:Client:new.html.twig")
     * @Method({"POST"})
     */
    public function saveAction(){
    	 
    
    	$form = $this->createForm(new Forms\ClientFormType(true));
    	
    	if($form->submit($this->getRequest()) && $form->isValid()){
    		
    		$client = $form->getData();
    		
    		$this->getDoctrine()->getManager()->persist($client);
    		$this->getDoctrine()->getManager()->flush();
    		
    		$this->get('session')->getFlashBag()->add('notice','Successfully Created Client.');
    		return $this->redirect($this->generateUrl('client_view',array('id' => $client->getId())));
    	} else {
    		$this->get('session')->getFlashBag()->add('error','Error Validating Form.');
    	}
    	
    	return array(
    		'form' => $form->createView(),
    		//'newUserForm' => $newUserForm->createView(),
    	);
    }
    /**
     * @Route("/clients/edit/{id}", name="spliced_pms_client_edit")
     * @Template()
     */
    public function editAction($id){

    }

    /**
     * @Route("/clients/view/{id}", name="spliced_pms_client_view")
     * @Template()
     */
    public function viewAction($id){
    	try{
    		$client = $this->getDoctrine()
    		->getRepository('SplicedProjectManagerBundle:Client')
    		->load($id);
    	}catch(\Doctrine\ORM\NoResultException $e){
    		throw $this->createNotFoundException('Client Not Found');
    	}
    	 
    	$form = $this->createForm(new Forms\ClientFormType(), $client);
    	
    	return array(
    		'client' => $client,
    		'form' => $form->createView(),
    	);
	}
	
	
    /**
     * @Route("/clients/delete/{id}", name="spliced_pms_client_delete")
     * @Template()
	 * @Method({"POST"})
     */
    public function deleteAction($id)
    {
	}
	
    /**
     * @Route("/clients/batch_action", name="spliced_pms_client_batch_action")
     * @Template()
     */
    public function batchActionAction($id)
    {
	}
}

<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Helper;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;
use Spliced\Bundle\ProjectManagerBundle\Entity\Project;
use Spliced\Bundle\ProjectManagerBundle\HttpFoundation\AjaxJsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NoResultException;

/**
 * Class ProjectController
 * @package Spliced\Bundle\ProjectManagerBundle\Controller\Admin
 */
class ProjectController extends Controller
{

    /**
     * @Route("/project", name="spliced_pms_project_list")
     * @Template()
     */
    public function listAction()
    {
		$projectsQuery = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
		  ->getListQuery();
		
		$pagination = $this->get('knp_paginator')->paginate(
		    $projectsQuery,
		    $this->get('request')->query->get('page', 1),
		    20
		);

        return array('pagination' => $pagination);
    }
    
    /**
     * @Route("/project/new", name="spliced_pms_project_new")
     * @Template()
     * @Method({"GET"})
     */
    public function newAction(){
    	$form = $this->createForm(new Forms\ProjectFormType(new Project()));
    	
    	return array('form' => $form->createView());
    }
    
    /**
     * @Route("/project/save", name="spliced_pms_project_save")
     * @Template("SplicedProjectManagerBundle:Project:new.html.twig")
     * @Method({"POST"})
     */
    public function saveAction(){
    	$form = $this->createForm(new Forms\ProjectFormType());
    	 
    	if($form->submit($this->getRequest()) && $form->isValid()){
    		$project = $form->getData();
    		
    		$this->getDoctrine()->getManager()->persist($project);
    		
    		try{
    			$this->getDoctrine()->getManager()->flush();
    			$this->get('session')->getFlashBag()->add('success','Project created successfully');
    			return $this->redirect($this->generateUrl('spliced_pms_project_view', array('id' => $project->getId())));
    		}catch(\Exception $e){
    			$this->get('session')->getFlashBag()->add('error','Error creating project. Possible duplicate url slug.');
    		}
    	} else {
    		$this->get('session')->getFlashBag()->add('error','There was an error validating your input');
    	}
    	
    	return array('form' => $form->createView());
    }
    
    /**
     * @Route("/project/{id}", name="spliced_pms_project_base")
     * @Route("/project/view/{id}", name="spliced_pms_project_view")
     * @Route("/project/edit/{id}", name="spliced_pms_project_edit")
     * @Template()
     */
    public function viewAction($id){
    	
    	try{
    		$project = $this->getDoctrine()
    		  ->getRepository('SplicedProjectManagerBundle:Project')
    		  ->load($id);
    	}catch(\Doctrine\ORM\NoResultException $e){
    		throw $this->createNotFoundException('Project Not Found');
    	}
    	
		$form = $this->createForm(new Forms\ProjectFormType($project), $project);

    	return array(
    		'project' => $project, 
    		'form' => $form->createView(),
		);
	}

    /**
     * @Route("/project/update/{id}", name="spliced_pms_project_update")
     * @Template("SplicedProjectManagerBundle:Project:view.html.twig")
     */
    public function updateAction($id){

		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			  ->findOneById($id);
		} catch(\Doctrine\ORM\NoResultException $e){

			throw $this->createNotFoundException('Project Not Found');
		}


		$form = $this->createForm(new Forms\ProjectFormType($project), $project);
		
		if($form->submit($this->get('request')) && $form->isValid()){
			
			$project = $form->getData();


			$this->getDoctrine()->getManager()->persist($project);
			$this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Project Updated');
            return $this->redirect($this->generateUrl('spliced_pms_project_view', array('id' => $project->getId())));
		} else {
			$this->get('session')->getFlashBag()->add('error', 'Error Validating Your Input');
		}

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
	}
	

    /**
     * @Route("/project/delete/{id}", name="spliced_pms_project_delete")
     * @Template()
     */
    public function deleteAction($project)
    {
	}
	
    /**
     * @Route("/project/batch_action", name="spliced_pms_project_batch_action")
     * @Template()
     */
    public function batchActionAction($id)
    {
	}
		
	/**
	 * @Route("/project/my_projects", name="spliced_pms_project_my_projects")
	 * @Template()
	 * @Method({"GET"})
	 */
	public function myProjectsAction(){
		
	}
	
	/**
	 * @Route("/project/needing_attention", name="spliced_pms_project_needing_attention")
	 * @Template()
	 * @Method({"GET"})
	 */
	public function needingAttentionAction(){
		
	}
	
	
	/**
	 * @Route("/project/check_slug", name="spliced_pms_project_check_slug")
	 * @Template()
	 * @Method({"POST"})
	 */
	public function checkSlugAction()
	{
		if(!$this->getRequest()->isXmlHttpRequest()){
			throw $this->createNotFoundException('Invalid Request Type');
		} else if(!$this->getRequest()->query->has('slug')){
			throw $this->createNotFoundException('No url slug provided');
		}
		
		$response = new AjaxJsonResponse(null,array('exists' => false, 'success' => true));

		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			  ->findOneByUrlSlug($this->getRequest()->query->get('slug'));
			
			if($project instanceof Project){
				$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
					'title' => 'Whoops',
					'body' => '<p>A Project with this same URL Slug already exists!</p>'
				))->getContent(), true)
				->setData('exists',true);
			}
		} catch(NoResultException $e){
			$response->setData('message','Does Not Exist');
		}
		return $response;
	}
}
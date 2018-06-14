<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;
use Spliced\Bundle\ProjectManagerBundle\HttpFoundation\AjaxJsonResponse;

class ProjectTagController extends Controller
{
	
    /**
     * @Route("/projects/{id}/tags/add", name="project_tag_add")
     * @Template()
     */
	public function addAction($id)
    {
    	$response = new AjaxJsonResponse(null,array('reset' => false));
		$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
			'title' => 'Whoops',
			'body' => '<p>There was an error processing the request.</p>'
		))->getContent(), true);
    	
		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			  ->findOneById($id);
		} catch(\Doctrine\ORM\NoResultException $e){
			if($this->getRequest()->isXmlHttpRequest()){
				$response->setContent(json_encode(array_merge($jsonResponse,array(
					'message' => 'Project Not Found.'))));
				return $response;
			}
			throw $this->createNotFoundException('Project Not Found');
		}

		$addForm = $this->createForm(new Forms\ProjectTagFormType());
		
		if($addForm->bindRequest($this->getRequest()) && $addForm->isValid()){
			
			$projectTag = $addForm->getData();
			
			$projectTag->setProject($project);
			
			$this->getDoctrine()->getManager()->persist($projectTag);
			$this->getDoctrine()->getManager()->flush();

			if($this->getRequest()->isXmlHttpRequest()){
				$response->setData('success',true)
				 ->setData('message','Tag Successfully Related.')
				 ->setData('target', 'project_tags')
				 ->setData('html',$this->render('SplicedProjectManagerBundle:ProjectTag:add_tag_ajax.html.twig',array(
				 	'projectTag' => $projectTag
				))->getContent(),true)
				 ->setData('modal', $this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
				 	'title' => 'Success',
				 	'body' => '<p>Tag has been successfully related to the project.</p>'
				))->getContent(),true);
				return $response;
			} else {
				$this->get('session')->getFlashBag()->add('success','Tag Successfully Related');
			}
		} else {
			if($this->getRequest()->isXmlHttpRequest()){
				$response->setData('message','There was an error validating your input.')
				  ->setData($this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
				  	'title' => 'Whoops',
				  	'body' => '<p>There was an error validating your input.</p>'
				))->getContent(), true);
				return $response;
			}
		}
		
        return $this->getRequest()->isXmlHttpRequest() ? 
        	$response : $this->redirect($this->generateUrl('project_view', array('id' => $project->getId())));
    }
	

    /**
     * @Route("/projects/{project}/tags/{tag}/delete", name="project_tag_delete")
    */
    public function removeAction($project, $tag)
    {
		$response = new AjaxJsonResponse(null,array('reset' => false));
		$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
			'title' => 'Whoops',
			'body' => '<p>There was an error processing the request.</p>'
		))->getContent(), true);
    	
		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			  ->findOneByIdWithTag($project,$tag);
		} catch(\Doctrine\ORM\NoResultException $e){
			if($this->getRequest()->isXmlHttpRequest()){
				$response->setContent(json_encode(array_merge($jsonResponse,array(
					'message' => 'Project Not Found.'))));
				return $response;
			}
			throw $this->createNotFoundException('Project and/or Tag Not Found');
		}
		
		foreach($project->getTags() as $tag){
			$this->getDoctrine()->getManager()->remove($tag);
		}
		
		$this->getDoctrine()->getManager()->flush();
		

		if(! $this->getRequest()->isXmlHttpRequest()){
			$this->get('session')->getFlashBag()->add('success','Service Successfully Unrelated');
			return $this->redirect($this->generateUrl('project_view', array('id' => $project->getId())));
		} 
		
		$response->setData('message','Tag Successfully Unrelated')
		  ->setData('success', true)
		  ->setData('remove', true)
		  ->setData('reset', false)
		  ->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
			'title' => 'Success',
			'body' => '<p>Tag Successfully Unrelated.</p>'
		  ))->getContent(), true);
		
		return $response;
	}
}

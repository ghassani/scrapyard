<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;
use Spliced\Bundle\ProjectManagerBundle\HttpFoundation\AjaxJsonResponse;
use Spliced\Bundle\ProjectManagerBundle\Event;

/**
 * @Route("/projects/{projectId}/media")
 */
class ProjectMediaController extends Controller
{
	
    /**
     * @Route("/add", name="spliced_pms_project_media_add")
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

		$addForm = $this->createForm(new Forms\ProjectMediaFormType());
		
		if($addForm->bindRequest($this->getRequest()) && $addForm->isValid()){
			
			$projectMedia = $addForm->getData();
			
			$projectMedia->setProject($project);
			
			$uploadedFile = $projectMedia->getFile();
			
			$this->get('event_dispatcher')->dispatch('spliced.project_media_upload', new Event\ProjectMediaUploadEvent($projectMedia));
			
			$this->getDoctrine()->getManager()->persist($projectMedia);
			$this->getDoctrine()->getManager()->flush();

			if($this->getRequest()->isXmlHttpRequest()){
				$response->setData('success',true)
				 ->setData('message','Media Successfully Added.')
				 ->setData('target', 'project_media')
				 ->setData('html',$this->render('SplicedProjectManagerBundle:ProjectMedia:add_media_ajax.html.twig',array(
				 	'projectMedia' => $projectMedia
				))->getContent(),true)
				 ->setData('modal', $this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
				 	'title' => 'Success',
				 	'body' => '<p>Media has been successfully added to the project.</p>'
				))->getContent(),true);
				return $response;
			} else {
				$this->get('session')->getFlashBag()->add('success','Meda Successfully Added');
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
	 * @Route("/{media}/delete", name="spliced_pms_project_media_delete")
	 */
	public function removeAction($project, $media)
	{
		$response = new AjaxJsonResponse(null,array('reset' => false));
		$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
				'title' => 'Whoops',
				'body' => '<p>There was an error processing the request.</p>'
		))->getContent(), true);
		 
		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			->findOneByIdWithMedia($project,$media);
		} catch(\Doctrine\ORM\NoResultException $e){
			if($this->getRequest()->isXmlHttpRequest()){
				$response->setContent(json_encode(array_merge($jsonResponse,array(
						'message' => 'Project Not Found.'))));
				return $response;
			}
			throw $this->createNotFoundException('Project and/or Media Not Found');
		}
	
		foreach($project->getMedia() as $_media){
			$this->getDoctrine()->getManager()->remove($_media);
			$this->get('event_dispatcher')->dispatch('spliced.project_media_delete', new Event\ProjectMediaDeleteEvent($_media));
		}
	
		$this->getDoctrine()->getManager()->flush();
	
	
		if(! $this->getRequest()->isXmlHttpRequest()){
			$this->get('session')->getFlashBag()->add('success','Media Successfully Deleted');
			return $this->redirect($this->generateUrl('project_view', array('id' => $project->getId())));
		}
	
		$response->setData('message','Media Successfully Deleted')
		->setData('success', true)
		->setData('remove', true)
		->setData('remove_target', '.project_media_'.$media)
		->setData('reset', false)
		->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
				'title' => 'Success',
				'body' => '<p>Media Successfully Deleted.</p>'
		))->getContent(), true);
	
		return $response;
	}
}

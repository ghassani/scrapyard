<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\ProjectInvoiceFormType;
use Spliced\Bundle\ProjectManagerBundle\Entity;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/project/{projectId}/invoice")
 */
class ProjectInvoiceController extends Controller
{
	/**
	 * @Route("/new", name="spliced_pms_project_invoice_new")
	 * @Template()
	 */
    public function newAction($projectId){

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'));
        }

        $form = $this->createForm(new ProjectInvoiceFormType());

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/save", name="spliced_pms_project_invoice_save")
     * @Template()
     */
    public function saveAction($projectId){

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'));
        }

        $form = $this->createForm(new ProjectInvoiceFormType());

        if ($form->submit($this->get('request')) && $form->isValid()) {

            $invoice = $form->getData();

            $invoice->setProject($project);

            $this->get('spliced_pms.project_invoice_manager')->save($invoice);

            return array(
                'form' => $form->createView(),
                'project' => $project,
                'invoice' => $invoice,
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
    }

	/**
	 * @Route("/line/add", name="spliced_pms_project_invoice_add_line")
	 * @Template()
	 */
	public function addLineItemAction(){
		if(!$this->getRequest()->isXmlHttpRequest()){
			throw $this->createNotFoundException('Action Method Not Allowed');
		}
		
		$response = new AjaxJsonResponse(null,array('reset' => false));		

		$projectInvoice = new Entity\ProjectInvoice();
		$projectInvoice->addLineItem(new Entity\ProjectInvoiceLineItem());
		
		$form = $this->createForm(new Forms\ProjectInvoiceFormType(), $projectInvoice);
		
		$response->setData('html', $this->render('SplicedProjectManagerBundle:ProjectInvoice:item_row_prototype.html.twig',array(
			'form' => $form->createView(), 
		))->getContent(), true)
		->setData('target', 'project_invoice_line_items')
		->setData('success', true)
		->setData('message', null);
		
		return $response;
	}
	
	/**
	 * @Route("/projects/{project}/invoice/{invoice}/edit", name="project_invoice_edit")
	 * @Template()
	 */
	public function editAction($project, $invoice){
	
	}
	
	/**
	 * @Route("/{invoice}/update", name="spliced_pms_project_invoice_update")
	 * @Method({"POST"})
	 * @Template()
	 */
	public function updateAction($project, $invoice){

	}
	
	/**
	 * @Route("/{invoice}/delete", name="spliced_pms_project_invoice_delete")
	 * @Template()
	 */
	public function removeAction($project, $invoice){
		$response = new AjaxJsonResponse(null,array('reset' => false));
		$response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
			'title' => 'Whoops',
			'body' => '<p>There was an error processing the request.</p>'
		))->getContent(), true);
			
		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			->findOneByIdWithInvoice($project,$invoice);
		} catch(\Doctrine\ORM\NoResultException $e){
			if($this->getRequest()->isXmlHttpRequest()){
				$response->setContent(json_encode(array_merge($jsonResponse,array(
					'message' => 'Project and/or Invoice Not Found.'))));
				return $response;
			}
			throw $this->createNotFoundException('Project and/or Invoice Not Found');
		}
		
		foreach($project->getInvoices() as $_invoice){
			$this->getDoctrine()->getManager()->remove($_invoice);
		}
		
		$this->getDoctrine()->getManager()->flush();
		
		
		if(! $this->getRequest()->isXmlHttpRequest()){
			$this->get('session')->getFlashBag()->add('success','Invoice Successfully Deleted');
			return $this->redirect($this->generateUrl('project_view', array('id' => $project->getId())));
		}
		
		$response->setData('message','Invoice Successfully Deleted')
		->setData('success', true)
		->setData('remove', true)
		->setData('remove_target', '.project_invoice_'.$invoice)
		->setData('reset', false)
		->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
			'title' => 'Success',
			'body' => '<p>Invoice Successfully Deleted.</p>'
		))->getContent(), true);
		
		return $response;
	}
}
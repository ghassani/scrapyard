<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectStaffEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\ProjectStaffFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\NoResultException;

/**
 * @Route("/project/{projectId}/staff")
 */
class ProjectStaffController extends Controller
{
	/**
	 * @Route("/new", name="spliced_pms_project_staff_new")
	 * @Template()
	 */
	public function newAction($projectId){

		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			->findOneById($projectId);
		
		} catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
		}

		$form = $this->createForm(new ProjectStaffFormType($project));

        return array(
            'project' => $project,
            'form'    => $form->createView(),
        );

    }


    /**
     * @Route("/save", name="spliced_pms_project_staff_save")
     * @Template()
     */
    public function saveAction($projectId){
        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        $form = $this->createForm(new ProjectStaffFormType($project));

		if($form->submit($this->getRequest()) && $form->isValid()){
			
			$projectStaff = $form->getData();

            $projectStaff->setProject($project);

            $this->get('spliced_pms.project_staff_manager')->save($projectStaff);

            return array(
                'form'      => $form->createView(),
                'project'   => $project,
                'projectStaff' => $projectStaff
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
	}
	
	/**
	 * @Route("/{projectStaffId}/edit", name="spliced_pms_project_staff_edit")
	 * @Template()
	 */
	public function editAction($projectId, $projectStaffId){

		try{
			$project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
			    ->findOneById($projectId);
			
		} catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
		}

        try{
            $projectStaff = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectStaff')
                ->findOneById($projectStaffId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Staff Not Found'), 404);
        }

		$form = $this->createForm(new ProjectStaffFormType($project), $projectStaff);
		

		return array(
			'form' => $form->createView(),
			'project' => $project,
            'projectStaff' => $projectStaff
		);
	}
	
	/**
	 * @Route("/{projectStaffId}/update", name="spliced_pms_project_staff_update")
	 * @Method({"POST"})
	 * @Template()
	 */
	public function updateAction($projectId, $projectStaffId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $projectStaff = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectStaff')
                ->findOneById($projectStaffId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Staff Not Found'), 404);
        }
		
		$form = $this->createForm(new ProjectStaffFormType($project), $projectStaff);
		
		if($form->submit($this->get('request')) && $form->isValid()){
			
			$projectStaff = $form->getData();
			
			$this->get('spliced_pms.project_staff_manager')->update($projectStaff);

            return array(
                'form' => $form->createView(),
                'project' => $project,
                'projectStaff' => $projectStaff
            );
		}

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
	}
	
	
	/**
	 * @Route("/{projectStaffId}/delete", name="spliced_pms_project_staff_delete")
	 * @Template()
	 */
	public function deleteAction($projectId, $projectStaffId){

			
		try{
			$project = $this->getDoctrine()
            ->getRepository('SplicedProjectManagerBundle:Project')
			->findOneById($projectId);
		
		} catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
		}

        try{
            $projectStaff = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectStaff')
                ->findOneById($projectStaffId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Staff Association Not Found'), 404);
        }

		$this->get('spliced_pms.project_staff_manager')->delete($projectStaff);

        return new JsonResponse(array('success' => true, 'id' => $projectStaff->getId()));
	}
}
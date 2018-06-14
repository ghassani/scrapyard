<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\ProjectNoteFormType;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/project/{projectId}/note")
 */
class ProjectNoteController extends Controller
{
	/**
	 * @Route("/new", name="spliced_pms_project_note_new")
	 * @Template()
	 */
	public function newAction($projectId){

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'));
        }

        $form = $this->createForm(new ProjectNoteFormType());

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
	}
    /**
     * @Route("/save", name="spliced_pms_project_note_save")
     * @Template()
     */
    public function saveAction($projectId){

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'));
        }

        $form = $this->createForm(new ProjectNoteFormType());

        if ($form->submit($this->get('request')) && $form->isValid()) {

            $note = $form->getData();

            $note->setProject($project);

            $this->get('spliced_pms.project_note_manager')->save($note);

            return array(
                'form' => $form->createView(),
                'project' => $project,
                'note' => $note,
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
    }



    /**
     * @Route("/{projectNoteId}/edit", name="spliced_pms_project_note_edit")
     * @Template()
     */
    public function editAction($projectId, $projectNoteId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $note = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectNote')
                ->findOneById($projectNoteId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Note Not Found'), 404);
        }

        $form = $this->createForm(new ProjectNoteFormType(), $note);

        return array(
            'project' => $project,
            'form' => $form->createView(),
            'note' => $note,
        );

    }

    /**
     * @Route("/{projectNoteId}/update", name="spliced_pms_project_note_update")
     * @Template()
     */
    public function updateAction($projectId, $projectNoteId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $note = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectNote')
                ->findOneById($projectNoteId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Note Not Found'), 404);
        }

        $form = $this->createForm(new ProjectNoteFormType(), $note);

        if ($form->submit($this->getRequest()) && $form->isValid()) {

            $note = $form->getData();

            $this->get('spliced_pms.project_note_manager')->update($note);

            return array(
                'form'      => $form->createView(),
                'project'   => $project,
                'note'      => $note
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));

    }

	/**
	 * @Route("/{projectNoteId}/delete", name="spliced_pms_project_note_delete")
	 * @Template()
	 */
	public function deleteAction($projectId, $projectNoteId){


        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'));
        }

        try{
            $note = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectNote')
                ->findOneById($projectNoteId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Note Not Found'), 404);
        }

        $this->get('spliced_pms.project_note_manager')->delete($note);

        return new JsonResponse(array('success' => true, 'id' => $note->getId()));
	}
}
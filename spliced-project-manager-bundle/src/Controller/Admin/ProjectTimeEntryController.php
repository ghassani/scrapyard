<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectTimeEntryEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\ProjectTimeEntryFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\NoResultException;

/**
 * @Route("/project/{projectId}/time_entry")
 */
class ProjectTimeEntryController extends Controller
{
    /**
     * @Route("/new", name="spliced_pms_project_time_entry_new")
     * @Template()
     */
    public function newAction($projectId){

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        $form = $this->createForm(new ProjectTimeEntryFormType($project));

        return array(
            'project' => $project,
            'form'    => $form->createView(),
        );

    }


    /**
     * @Route("/save", name="spliced_pms_project_time_entry_save")
     * @Template()
     */
    public function saveAction($projectId){
        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        $form = $this->createForm(new ProjectTimeEntryFormType($project));

        if($form->submit($this->getRequest()) && $form->isValid()){

            $projectTimeEntry = $form->getData();

            $projectTimeEntry->setProject($project);

            $this->get('spliced_pms.project_time_entry_manager')->save($projectTimeEntry);

            return array(
                'form'      => $form->createView(),
                'project'   => $project,
                'projectTimeEntry' => $projectTimeEntry
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
    }

    /**
     * @Route("/{projectTimeEntryId}/edit", name="spliced_pms_project_time_entry_edit")
     * @Template()
     */
    public function editAction($projectId, $projectTimeEntryId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $projectTimeEntry = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectTimeEntry')
                ->findOneById($projectTimeEntryId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Time Entry Not Found'), 404);
        }

        $form = $this->createForm(new ProjectTimeEntryFormType($project), $projectTimeEntry);


        return array(
            'form' => $form->createView(),
            'project' => $project,
            'projectTimeEntry' => $projectTimeEntry
        );
    }

    /**
     * @Route("/{projectTimeEntryId}/update", name="spliced_pms_project_time_entry_update")
     * @Method({"POST"})
     * @Template()
     */
    public function updateAction($projectId, $projectTimeEntryId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $projectTimeEntry = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectTimeEntry')
                ->findOneById($projectTimeEntryId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Time Entry Not Found'), 404);
        }

        $form = $this->createForm(new ProjectTimeEntryFormType($project), $projectTimeEntry);

        if($form->submit($this->get('request')) && $form->isValid()){

            $projectTimeEntry = $form->getData();

            $this->get('spliced_pms.project_time_entry_manager')->update($projectTimeEntry);

            return array(
                'form' => $form->createView(),
                'project' => $project,
                'projectTimeEntry' => $projectTimeEntry
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
    }


    /**
     * @Route("/{projectTimeEntryId}/delete", name="spliced_pms_project_time_entry_delete")
     * @Template()
     */
    public function deleteAction($projectId, $projectTimeEntryId){


        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $projectTimeEntry = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectTimeEntry')
                ->findOneById($projectTimeEntryId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Time Entry Association Not Found'), 404);
        }

        $this->get('spliced_pms.project_time_entry_manager')->delete($projectTimeEntry);

        return new JsonResponse(array('success' => true, 'id' => $projectTimeEntry->getId()));
    }
}
<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\ProjectAttributeFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\NoResultException;

class ProjectAttributeController extends Controller
{
	/**
	 * @Route("/project/{projectId}/attribute/new", name="spliced_pms_project_attribute_new")
	 * @Template()
	 */
	public function newAction($projectId){
		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			->findOneById($projectId);
		
		} catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'));
		}

		$form = $this->createForm(new ProjectAttributeFormType());

		return array(
			'project' => $project,
			'form' => $form->createView(),	
		);
	}
	
	/**
	 * @Route("/project/{projectId}/attribute/save", name="spliced_pms_project_attribute_save")
	 * @Template()
	 */
	public function saveAction($projectId){


		try{
			$project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
			->findOneById($projectId);
				
		} catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
		}

		$form = $this->createForm(new ProjectAttributeFormType());

        $form->submit($this->get('request'));

		if($form->isValid()){
			
			$attribute = $form->getData();

            $attribute->setProject($project);

			$this->get('spliced_pms.project_attribute_manager')->save($attribute);

            return array(
                'form'      => $form->createView(),
                'project'   => $project,
                'attribute' => $attribute
            );
		}
		
        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
	}

    /**
     * @Route("/project/{projectId}/attribute/{attributeId}/edit", name="spliced_pms_project_attribute_edit")
     * @Template()
     */
    public function editAction($projectId, $attributeId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $attribute = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectAttribute')
                ->findOneById($attributeId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Attribute Not Found'), 404);
        }

        $form = $this->createForm(new ProjectAttributeFormType(), $attribute);

        return array(
            'project' => $project,
            'form' => $form->createView(),
            'attribute' => $attribute,
        );

    }

    /**
     * @Route("/project/{projectId}/attribute/{attributeId}/update", name="spliced_pms_project_attribute_update")
     * @Template()
     */
    public function updateAction($projectId, $attributeId){

        try{
            $project = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        try{
            $attribute = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectAttribute')
                ->findOneById($attributeId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Attribute Not Found'), 404);
        }

        $form = $this->createForm(new ProjectAttributeFormType(), $attribute);

        if ($form->submit($this->getRequest()) && $form->isValid()) {

            $attribute = $form->getData();

            $this->get('spliced_pms.project_attribute_manager')->update($attribute);

            return array(
                'form'      => $form->createView(),
                'project'   => $project,
                'attribute' => $attribute
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));

    }

	/**
	 * @Route("/project/{projectId}/attribute/{attributeId}/delete", name="spliced_pms_project_attribute_delete")
	 * @Template()
	 */
	public function deleteAction($projectId, $attributeId){

		try{
			$project = $this->getDoctrine()
            ->getRepository('SplicedProjectManagerBundle:Project')
			->findOneById($projectId);
		
		} catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
		}

        try{
            $attribute = $this->getDoctrine()
                ->getRepository('SplicedProjectManagerBundle:ProjectAttribute')
                ->findOneById($attributeId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Attribute Not Found'), 404);
        }

        $this->get('spliced_pms.project_attribute_manager')->delete($attribute);

        return new JsonResponse(array('success' => true, 'id' => $attribute->getId()));
	}
}
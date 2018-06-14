<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Form\Type\ProjectFileFormType;
use Spliced\Bundle\ProjectManagerBundle\Event\Event;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectFileEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/projects/{projectId}/file")
 */
class ProjectFileController extends Controller
{

    /**
     * @Route("/new", name="spliced_pms_project_file_new")
     * @Template()
     */
    public function newAction($projectId)
    {

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        $form = $this->createForm(new ProjectFileFormType($project));

        return array(
            'project' => $project,
            'form'    => $form->createView(),
        );

    }

    /**
     * @Route("/save", name="spliced_pms_project_file_save")
     * @Template()
     */
    public function saveAction($projectId)
    {
        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneById($projectId);

        } catch(NoResultException $e){
            return new JsonResponse(array('success' => false, 'message' => 'Project Not Found'), 404);
        }

        $form = $this->createForm(new ProjectFileFormType($project));

        if($form->submit($this->getRequest()) && $form->isValid()){

            $projectFile = $form->getData();

            $event = $this->get('event_dispatcher')->dispatch(
                Event::PROJECT_FILE_SAVE,
                new ProjectFileEvent($project, $projectFile)
            );

            return array(
                'form'      => $form->createView(),
                'project'   => $project,
                'projectFile' => $projectFile
            );
        }

        return new JsonResponse(array('success' => false, 'message' => 'Check Your Input'));
    }

    /**
     * @Route("/{fileId}/delete", name="spliced_pms_project_file_delete")
     */
    public function removeAction($project, $file)
    {
        $response = new AjaxJsonResponse(null,array('reset' => false));
        $response->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
            'title' => 'Whoops',
            'body' => '<p>There was an error processing the request.</p>'
        ))->getContent(), true);

        try{
            $project = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Project')
                ->findOneByIdWithFile($project,$file);
        } catch(\Doctrine\ORM\NoResultException $e){
            if($this->getRequest()->isXmlHttpRequest()){
                return $response;
            }
            throw $this->createNotFoundException('Project and/or Media Not Found');
        }

        foreach($project->getFiles() as $_file){
            $this->getDoctrine()->getManager()->remove($_file);
            $this->get('event_dispatcher')->dispatch('spliced.project_file_delete', new Event\ProjectFileDeleteEvent($_file));
        }

        $this->getDoctrine()->getManager()->flush();


        if(! $this->getRequest()->isXmlHttpRequest()){
            $this->get('session')->getFlashBag()->add('success','File Successfully Deleted');
            return $this->redirect($this->generateUrl('project_view', array('id' => $project->getId())));
        }

        $response->setData('message','File Successfully Deleted')
            ->setData('success', true)
            ->setData('remove', true)
            ->setData('remove_target', '.project_file_'.$file)
            ->setData('reset', false)
            ->setData('modal',$this->render('SplicedProjectManagerBundle:Common:modal.html.twig',array(
                'title' => 'Success',
                'body' => '<p>File Successfully Deleted.</p>'
            ))->getContent(), true);

        return $response;
    }

    /**
     * @Route("/{file}/download", name="spliced_pms_project_file_download")
     */
    public function downloadAction($file){
        $em = $this->get('doctrine.orm.entity_manager');
        $appConfig = $this->get('app.config');

        try{
            $projectFile = $em->getRepository('SplicedProjectManagerBundle:ProjectFile')
                ->findOneById($file);

        }catch(NoResultException $e){
            $this->get('session')->getFlashBag()->add('error', 'File Does Not Exists');
            return $this->redirect($this->get('request')->server->get('HTTP_REFERER'));
        }

        $fileStoragePath = $appConfig->get('file_storage_path');
        $filePath = $fileStoragePath.DIRECTORY_SEPARATOR.$projectFile->getFilename();

        if(! file_exists($filePath)){
            $this->get('session')->getFlashBag()->add('error', 'File Does Not Exists');
            return $this->redirect($this->get('request')->server->get('HTTP_REFERER'));
        }

        // send the file
        $response = new Response(file_get_contents($filePath),200);
        $response->headers->set('Content-Type',$projectFile->getFileType());
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $projectFile->getOriginalFilename()));
        return $response;
    }
}

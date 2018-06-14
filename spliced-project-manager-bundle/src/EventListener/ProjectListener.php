<?php 
namespace Spliced\Bundle\ProjectManagerBundle\EventListener;

use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationManagerInterface;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectAttributeEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectFileEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectInvoiceEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectMediaEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectMessageEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectTagEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectQuoteEvent;
use Spliced\Bundle\ProjectManagerBundle\Event\ProjectStaffEvent;
use Doctrine\ORM\EntityManager;

class ProjectListener
{

    protected $em;
    protected $configurationManager;

	/**
	 * __construct
	 * 
	 * @param $container 
	 */
	public function __construct(EntityManager $em, ConfigurationManagerInterface $configurationManager){
        $this->em = $em;
        $this->configurationManager = $configurationManager;
	}

    /**
     * @param ProjectAttributeEvent $event
     */
    public function onProjectAttributeSave(ProjectAttributeEvent $event)
    {

    }

    /**
     * @param ProjectAttributeEvent $event
     */
    public function onProjectAttributeUpdate(ProjectAttributeEvent $event)
    {

    }

    /**
     * @param ProjectAttributeEvent $event
     */
    public function onProjectAttributeDelete(ProjectAttributeEvent $event)
    {

    }

    /**
     * @param ProjectStaffEvent $event
     */
    public function onProjectStaffSave(ProjectStaffEvent $event)
    {

        $event->getProjectStaff()->setProject($event->getProject());

        $this->em->persist($event->getProjectStaff());

        $this->em->flush();
    }

    /**
     * @param ProjectStaffEvent $event
     */
    public function onProjectStaffUpdate(ProjectStaffEvent $event)
    {
        $this->em->persist($event->getProjectStaff());

        $this->em->flush();
    }

    /**
     * @param ProjectStaffEvent $event
     */
    public function onProjectStaffDelete(ProjectStaffEvent $event)
    {
        $this->em->remove($event->getProjectStaff());

        $this->em->flush();
    }

    /**
     * @param ProjectFileEvent $event
     */
    public function onProjectFileSave(ProjectFileEvent $event)
    {
        $event->getProjectFile()->setProject($event->getProject());

        $this->em->persist($event->getProjectFile());

        $this->em->flush();
    }

    /**
     * @param ProjectFileEvent $event
     */
    public function onProjectFileUpdate(ProjectFileEvent $event)
    {
        $this->em->persist($event->getProjectFile());

        $this->em->flush();
    }

    /**
     * @param ProjectFileEvent $event
     */
    public function onProjectFileDelete(ProjectFileEvent $event)
    {
        $this->em->remove($event->getProjectFile());

        $this->em->flush();
    }

	/**
	 * onMediaUpload
	 * 
	 * @param ActivistActionEvent $event
	 */
	public function onMediaUpload(Event\ProjectMediaUploadEvent $event){
		$projectMedia = $event->getProjectMedia();
		$uploadedFile = $event->getUploadedFile();
		$appConfig = $this->container->get('app.config');
		
		$projectMedia->setFileType($uploadedFile->getClientMimeType());

		$projectMediaDir = $appConfig->get('web_path').DIRECTORY_SEPARATOR.$appConfig->get('project_media_web_rel_path');
		
		if(! $extension = $uploadedFile->getExtension() ){
			$extension = @end(explode('.',$uploadedFile->getClientOriginalName()));
		}

		$filename = sprintf('%s_%s_%s.%s',$projectMedia->getProject()->getId(),$projectMedia->getDisplayType(),time(),$extension);
		
		$uploadedFile->move($projectMediaDir,$filename);
		
		$projectMedia->setFilename($filename);
		
	}
	
	
	/**
	 * onMediaDelete
	 *
	 * @param ProjectMediaDeleteEvent $event
	 */
	public function onMediaDelete(Event\ProjectMediaDeleteEvent $event){
		$projectMedia = $event->getProjectMedia();
		$appConfig = $this->container->get('app.config');
		
		$filePath = sprintf('%s/%s/%s', $appConfig->get('web_path'),$appConfig->get('project_media_web_rel_path'), $projectMedia->getFilename());
		
		if(file_exists($filePath)){
			@unlink($filePath);
		}
	}
	
	
	/**
	 * onFileUpload
	 *
	 * @param ProjectFileUploadEvent $event
	 */
	public function onFileUpload(Event\ProjectFileUploadEvent $event){
		$projectFile = $event->getProjectFile();
		$uploadedFile = $event->getUploadedFile();
		$appConfig = $this->container->get('app.config');
	
		$projectFile->setFileType($uploadedFile->getClientMimeType());
	
		$projectFileDir = $appConfig->get('file_storage_path');
	
		if(! $extension = $uploadedFile->getExtension() ){
			$extension = @end(explode('.',$uploadedFile->getClientOriginalName()));
		}
	
		$filename = sprintf('%s_%s.%s',$projectFile->getProject()->getId(),time(),$extension);
	
		$uploadedFile->move($projectFileDir,$filename);
	
		$projectFile->setFilename($filename);
		$projectFile->setOriginalFilename($uploadedFile->getClientOriginalName());
		
	}
	
	
	/**
	 * onFileDelete
	 *
	 * @param ProjectFileDeleteEvent $event
	 */
	public function onFileDelete(Event\ProjectFileDeleteEvent $event){
		$projectFile = $event->getProjectFile();
		$appConfig = $this->container->get('app.config');
	
		$filePath = sprintf('%s/%s', $appConfig->get('file_storage_path'), $projectFile->getFilename());
	
		if(file_exists($filePath)){
			@unlink($filePath);
		}
	}
}
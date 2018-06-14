<?php
namespace Spliced\Bundle\MediaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\MediaManagerBundle\Model\RepositoryNotFoundException;
use Spliced\Bundle\MediaManagerBundle\Form\Type\UploadFormType;

class UploadController extends Controller
{
    /**
	 * @Route("/process_upload", name="media_manager_process_upload")
     * @Template()
     */
    public function indexAction()
    {
    	$mediaManager = $this->get('spliced_media_manager.manager');
    	
    	$form = $this->createForm(new UploadFormType());
    	$form->bind($this->getRequest());
		
    	$formData = $form->getData();
    	
    	$repositories = $mediaManager->getRepositories();
    	
    	try{
    		$repository = $mediaManager->findRepositoryByName($formData['repository']);
    	}catch(RepositoryNotFoundException $e){
    		$repository = $repositories->first();
    	}
    	
    	$folder = '';
    	if($formData['subPath']){
    		$folder .= urldecode($formData['subPath']);
    	}

    	$this->get('punk_ave.file_uploader')->handleFileUpload(array(
    		'folder' => $folder,
    		'file_base_path' => '/'.$repository->getPath(),
    		'web_base_path' =>  $repository->getWebPath()
    	));
    }
}
<?php
namespace Spliced\Bundle\MediaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\MediaManagerBundle\Model\RepositoryNotFoundException;
use Spliced\Bundle\MediaManagerBundle\Model\Directory;
use Spliced\Bundle\MediaManagerBundle\Model\File;
use Spliced\Bundle\MediaManagerBundle\Form\Type\UploadFormType;
use Symfony\Component\Finder\SplFileInfo;

class DeleteController extends Controller
{
    /**
	 * @Route("/delete/{repo}/{fileName}", name="media_manager_delete", defaults={"repo"="%spliced_media_manager.default_repository%"})
     * @Template("SplicedMediaManagerBundle:Default:index.html.twig")
     */
    public function indexAction($repo, $fileName)
    {
    	$referer = $this->getRequest()->headers->get('referer');  
		$mediaManager = $this->get('spliced_media_manager.manager');
		$requestQuery = $this->getRequest()->query;
		$repositories = $mediaManager->getRepositories();

		try{
			$repository = $mediaManager->findRepositoryByName($repo);
		}catch(RepositoryNotFoundException $e){
			$repository = $repositories->first();
		}
		
		$subPath = $requestQuery->get('subpath',null);
		
		$currentDirectory = new Directory(new SplFileInfo($subPath ? $repository->getPath().DIRECTORY_SEPARATOR.$subPath : $repository->getPath(),null,null), $repository);
    	
		$filePath = $currentDirectory->getRealPath().DIRECTORY_SEPARATOR.$fileName;
		
		if(!$mediaManager->exists($filePath)){
    		$this->get('session')->getFlashBag()->add('error', 'File Does Not Exist!');
			return $this->redirect($referer ? $referer : $this->generateUrl('media_manager_index'));
		}
		
    	$currentFile = new File(new SplFileInfo($filePath, null, null), $repository);
		
		$mediaManager->remove($currentFile->getRealPath());
		
    	$this->get('session')->getFlashBag()->add('success', 'FIle(s) Deleted Successfully');
		return $this->redirect($referer ? $referer : $this->generateUrl('media_manager_index'));
	}
	
}
    	
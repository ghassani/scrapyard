<?php
namespace Spliced\Bundle\MediaManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\MediaManagerBundle\Model\RepositoryNotFoundException;
use Spliced\Bundle\MediaManagerBundle\Model\Directory;
use Spliced\Bundle\MediaManagerBundle\Form\Type\UploadFormType;
use Symfony\Component\Finder\SplFileInfo;

class DefaultController extends Controller
{
    /**
	 * @Route("/list/{repo}", name="media_manager_index", defaults={"repo"="%spliced_media_manager.default_repository%"})
     * @Template("SplicedMediaManagerBundle:Default:index.html.twig")
     */
    public function indexAction($repo)
    {
    	
		$mediaManager = $this->get('spliced_media_manager.manager');
		$requestQuery = $this->getRequest()->query;
		
		$repositories = $mediaManager->getRepositories();

		try{
			$repository = $mediaManager->findRepositoryByName($repo);
		}catch(RepositoryNotFoundException $e){
			$repository = $repositories->first();
		}
		
		$subPath = $requestQuery->get('subpath',null);
		$repositoryFiles = $mediaManager->getDirectoryTree($repository, $subPath);
		
		$currentDirectory = new Directory(new SplFileInfo($subPath ? $repository->getPath().DIRECTORY_SEPARATOR.$subPath : $repository->getPath(),null,null), $repository);
    	
		$form = $this->createForm(new UploadFormType());
		
		$form->setData(array('repository' => $repository->getName(), 'subPath' => $subPath));
		
        return array(
        	'files' => $repositoryFiles, 
        	'layout' => $this->container->getParameter('spliced_media_manager.layout'),
			'repositories' => $repositories,
			'repository' => $repository,
			'form' => $form->createView(),
        	'currentDirectory' => $currentDirectory,
		);
    }

}
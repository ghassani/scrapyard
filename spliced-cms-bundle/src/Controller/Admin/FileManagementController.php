<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Controller\Admin;

use Spliced\Bundle\CmsBundle\Form\Type\FolderFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Finder\SplFileInfo;
use Spliced\Bundle\CmsBundle\Form\Type\FileFormType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/%spliced_cms.admin_route_prefix%")
 */
class FileManagementController extends Controller
{
    /**
     * @var SplFileInfo
     */
    protected $dir;

    /**
     * @var Finder
     */
    protected $files;

    /**
     * @var SplFileInfo
     */
    protected $baseDir = null;

    /**
     * @var SplFileInfo|null
     */
    protected $file = null;

    /**
     * @var bool - If context has been loaded
     */
    protected $loaded = false;

    /**
     * loadContext
     *
     * @return array
     */
    private function loadContext()
    {
        $this->files = new Finder();
        $this->baseDir = new SplFileInfo(
            $this->get('spliced_cms.site_manager')->getCurrentAdminSite()->getRootDir(),
            '/',
            '/'
        );
        if ($this->get('request')->query->has('dir')) {
            $path = $this->baseDir->getRealPath() . '/'. $this->get('request')->query->get('dir');
            $relativePath = preg_replace('/\/{2,}/', '/', str_replace($this->baseDir->getRealPath(), '', $path));
            $this->dir = new SplFileInfo(
                $path,
                $relativePath,
                $relativePath
            );
        } else {
            $this->dir = $this->baseDir;
        }

        $this->files->followLinks()->depth(0)->in($this->dir->getRealPath());
        $this->file = null;
        
        if ($this->get('request')->query->has('file') && $this->get('request')->query->get('file')) {
            $this->file = new SplFileInfo(
                $this->dir->getRealPath().'/'.$this->get('request')->query->get('file'),
                $this->dir->getRelativePath().'/'.$this->get('request')->query->get('file'),
                $this->dir->getRelativePathname()
            );
        }

        $this->loaded = true;
        return $this;
    }

    /**
     * @return array
     */
    protected function getViewContext()
    {
        if (false === $this->loaded) {
            $this->loadContext();
        }
        return array(
            'files'     => $this->files,
            'baseDir'   => $this->baseDir,
            'dir'       => $this->dir,
            'file'      => $this->file,
        );
    }

    /**
     * @Route("/file_manager", name="spliced_cms_admin_file_manager")
     * @Template()
     */
    public function indexAction()
    {
        return $this->getViewContext();
    }

    /**
     * @Route("/file_manager/download", name="spliced_cms_admin_file_manager_download")
     * @Template()
     */
    public function downloadAction()
    {
        $this->loadContext();
        $file = $this->file;
        if (is_null($this->file) || false === $this->file->getRealPath() ) {
            $this->get('session')->getFlashBag()->add('error', 'File Not Found');
            return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager', array(
                'dir' => str_replace($this->baseDir->getRealPath(), '', $this->dir->getRealPath())
            )));
        }
        $file = $this->file;
        $response = new StreamedResponse(function () use ($file) {
            echo $file->getContents();
        });
        $info = new \finfo(FILEINFO_MIME_TYPE);
        $response->headers->set('Content-Type', $info->file($file->getRealPath()));
        $response->headers->set('Cache-Control', '');
        $response->headers->set('Content-Length', $file->getSize());
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s'));
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getFilename()));
        $response->prepare($this->get('request'));
        return $response;
    }

    /**
     * @Route("/file_manager/edit-file", name="spliced_cms_admin_file_manager_edit_file")
     * @Template("SplicedCmsBundle:Admin/FileManagement:edit_file.html.twig")
     */
    public function editFileAction()
    {
        $this->loadContext();
        if (!$this->file instanceof \SplFileInfo || !$this->file->getRealPath() ) {
            return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager', array(
                'dir' => $this->baseDir->getRelativePath()
            )));
        }
        $fileFormData = array('content' => $this->file->getContents());
        $fileForm = $this->createForm(new FileFormType($this->file), $fileFormData);
        return array_merge($this->getViewContext(), array(
            'fileForm'  => $fileForm->createView(),
        ));
    }

    /**
     * @Route("/file_manager/edit-file/update", name="spliced_cms_admin_file_manager_update_file")
     * @Template("SplicedCmsBundle:Admin/FileManagement:edit_file.html.twig")
     */
    public function updateFileAction()
    {
        return $this->getViewContext();
    }

    /**
     * @Route("/file_manager/delete-file", name="spliced_cms_admin_file_manager_delete_file")
     * @Template()
     */
    public function deleteFileAction()
    {
        $this->loadContext();
        if (!$this->file instanceof \SplFileInfo || !$this->file->getRealPath() || $this->file->isDir() ) {
            $this->get('session')->getFlashBag()->add('error', 'File Not Found');
            return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager', array(
                'dir' => $this->dir->getRelativePath()
            )));
        }
        if (!@unlink($this->file->getRealPath())) {
            $this->get('session')->getFlashBag()->add('error', 'Could Not Delete File. Permission Denied');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'File Deleted');
        }
        return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager', array(
            'dir' => $this->dir->getRelativePath()
        )));
    }

    /**
     * @Route("/file_manager/delete-folder", name="spliced_cms_admin_file_manager_delete_folder")
     * @Template()
     */
    public function deleteFolderAction()
    {
        $this->loadContext();
        if (!$this->dir instanceof \SplFileInfo || !$this->dir->getRealPath() || !$this->file->isDir() ) {
            $this->get('session')->getFlashBag()->add('error', 'Directory Not Found');
            return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager', array(
                'dir' => $this->dir->getRelativePath()
            )));
        }
        $fs = new Filesystem();
        $success = false;
        try {
            $fs->remove($this->dir->getRealPath());
            $this->get('session')->getFlashBag()->add('success', 'File Deleted');
            $success = true;
        } catch(\IOException $e) {
            $this->get('session')->getFlashBag()->add('error', 'Could Not Delete Folder');
        }
        return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager', array(
            'dir' => $success ? $this->baseDir->getRelativePath() : $this->dir->getRelativePath(),
        )));
    }

    /**
     * @Route("/file_manager/new-file", name="spliced_cms_admin_file_manager_new_file")
     * @Template("SplicedCmsBundle:Admin/FileManagement:new_file.html.twig")
     */
    public function newFileAction()
    {
        $view = $this->getViewContext();
        $fileForm = $this->createForm(new FileFormType(null), array());
        return array_merge($view, array(
            'fileForm' => $fileForm->createView(),
        ));
    }

    /**
     * @Route("/file_manager/new-file/save", name="spliced_cms_admin_file_manager_save_file")
     * @Template("SplicedCmsBundle:Admin/FileManagement:new_file.html.twig")
     */
    public function saveFileAction()
    {
        $view = $this->getViewContext();
        $fileForm = $this->createForm(new FileFormType(null), array());
        if ($fileForm->submit($this->get('request')) && $fileForm->isValid())                 {
                $file = $fileForm->getData();
            $fileInfo = new \SplFileInfo($this->dir->getRealPath().'/'.$file['fileName']);
            $relativePath = str_replace($this->baseDir->getRealPath(), '', $fileInfo->getPath());
            $wrote = file_put_contents($fileInfo->getPath().'/'.$fileInfo->getFilename(), $file['content']);
            if (false === $wrote) {
                $this->get('session')->getFlashBag()->add('error', 'There was an error saving the file. Permission Denied');
            } else {
                return $this->redirect($this->generateUrl('spliced_cms_admin_file_manager_edit_file', array(
                    'dir' => $relativePath,
                    'file' => $fileInfo->getFilename()
                )));
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'There was an error validating your submission');
        }
        return array_merge($view, array(
            'fileForm' => $fileForm->createView(),
        ));
    }

    /**
     * @Route("/file_manager/new-folder", name="spliced_cms_admin_file_manager_new_folder")
     * @Template("SplicedCmsBundle:Admin/FileManagement:new_folder.html.twig")
     */
    public function newFolderAction()
    {
        $this->loadContext();
        $folderForm = $this->createForm(new FolderFormType(), array());
        return array_merge($this->getViewContext(), array(
            'folderForm' => $folderForm->createView(),
        ));
    }
    
    /**
     * @Route("/file_manager/new-folder/create", name="spliced_cms_admin_file_manager_create_folder")
     * @Template("SplicedCmsBundle:Admin/FileManagement:new_folder.html.twig")
     */
    public function createFolderAction()
    {
        $this->loadContext();
        $folderForm = $this->createForm(new FolderFormType(), array());
        if ($folderForm->submit($this->get('request')) && $folderForm->isValid()) {
            $this->get('session')->getFlashBag()->add('error', 'There was an error validating your submission');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'There was an error validating your submission');
        }
        return array_merge($this->getViewContext(), array(
            'folderForm' => $folderForm->createView(),
        ));
    }
}

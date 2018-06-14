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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/gallery")
 */
class GalleryController extends Controller
{
    /**
     * @Route("/", name="spliced_cms_admin_gallery")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $galleryQuery = $this->get('spliced_cms.gallery_manager')
            ->getGalleryQuery($this->get('spliced_cms.site_manager')->getCurrentAdminSite());
        $gallery = $this->get('knp_paginator')->paginate(
            $galleryQuery,
            $this->get('request')->query->get('page', 1),
            25
        );
        return array(
            'gallery' => $gallery,
        );
    }

    /**
     * @Route("/sync", name="spliced_cms_admin_gallery_sync")
     * @Template()
     */
    public function syncAction(Request $request)
    {
        $this->get('spliced_cms.gallery_manager')
            ->sync($this->get('spliced_cms.site_manager')->getCurrentAdminSite());
        $this->get('session')->getFlashBag()->add('success', 'Site Gallery Updated');
        return $this->redirect($this->generateUrl('spliced_cms_admin_gallery'));
    }

    /**
     * @Route("/json", name="spliced_cms_admin_gallery_json")
     */
    public function galleryJsonAction(Request $request)
    {
        $galleryManager = $this->get('spliced_cms.gallery_manager');
        $webDir = $galleryManager->getWebDir();
        $cacheDir = $galleryManager->getCacheDir();
        foreach ($galleryManager->getImages() as $image) {
            $images[] = array(
                'full' => str_replace($webDir->getRealPath(), '', $image['original']->getRealPath()),
                'thumbnail' => str_replace($webDir->getRealPath(), '', $image['cache']->getRealPath()),
                'type' => $image['original']->getExtension(),
                'size' => $image['original']->getSize(),
                'modified' => $image['original']->getMTime(),
                'created' => $image['original']->getCTime(),
                'filename' => $image['original']->getFilename(),
                'width' => $image['width'],
                'height' => $image['height'],
                'cache_width' => $image['cache_width'],
                'cache_height' => $image['cache_height'],
            );
        }
        return new JsonResponse(array(
            'success' => true,
            'data' => $images
        ));
    }
    
}

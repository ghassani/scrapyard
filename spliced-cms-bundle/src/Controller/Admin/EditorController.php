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
use Symfony\Component\HttpFoundation\Response;
use Spliced\Bundle\CmsBundle\Form\Type\EditorInsertLinkType;
use Spliced\Bundle\CmsBundle\Form\Type\EditorInsertVideoType;
use Spliced\Bundle\CmsBundle\Form\Type\EditorInsertImageType;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/editor")
 */
class EditorController extends Controller
{

    /**
     * @Route("/insert-link", name="spliced_cms_admin_editor_insert_link")
     * @Template("SplicedCmsBundle:Admin/Editor:insert_link.html.twig")
     */
    public function insertLinkAction()
    {
        $formType = new EditorInsertLinkType($this->get('spliced_cms.site_manager'), $this->get('doctrine.orm.entity_manager'));
        
        $form = $this->createForm($formType, array());
        
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/insert-image", name="spliced_cms_admin_editor_insert_image")
     * @Template("SplicedCmsBundle:Admin/Editor:insert_image.html.twig")
     */
    public function insertImageAction()
    {
        $galleryQuery = $this->get('spliced_cms.gallery_manager')
            ->getGalleryQuery($this->get('spliced_cms.site_manager')->getCurrentAdminSite());
        
        $gallery = $this->get('knp_paginator')->paginate(
            $galleryQuery,
            $this->get('request')->query->get('page', 1),
            10
        );

        return array(
            'gallery' => $gallery,
            'form' => $this->createForm(new EditorInsertImageType(), array())->createView()
        );
    }
    
    /**
     * @Route("/insert-video", name="spliced_cms_admin_editor_insert_video")
     * @Template("SplicedCmsBundle:Admin/Editor:insert_video.html.twig")
     */
    public function insertVideoEmbedAction()
    {
        $formType = new EditorInsertVideoType();
        
        $form = $this->createForm($formType, array());
        
        return array(
            'form' => $form->createView()
        );
    }
}

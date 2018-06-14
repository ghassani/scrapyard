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
use Spliced\Bundle\CmsBundle\Entity\Layout;
use Spliced\Bundle\CmsBundle\Entity\Template as CmsTemplate;
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;
use Spliced\Bundle\CmsBundle\Form\Type\LayoutFormType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/json")
 */
class JsonController extends Controller
{
    /**
     * @Route("/template_version/load/{id}", name="spliced_cms_admin_json_template_version_load")
     */
    public function templateVersionLoadAction($id)
    {
        $response = new JsonResponse();
        $version = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:TemplateVersion')->findOneByIdForJson($id);
        if (!$id){
            return new JsonResponse(array(
                'success' => false,
                'message' => 'Template Version Not Found With ID '.$id
            ));
        }
        $version['content'] = rawurlencode($version['content']);
        return new JsonResponse(array(
            'success' => true,
            'data' => $version
        ));
    }

    /**
     * @Route("/content_page/load", name="spliced_cms_admin_json_content_page_load")
     */
    public function contentPagesLoadAction()
    {
        $response = new JsonResponse();
        $pages = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:ContentPage')->findAllBySiteForJson($this->get('spliced_cms.site_manager')->getCurrentAdminSite());
        return new JsonResponse(array(
            'success' => true,
            'data' => $pages
        ));
    }

    /**
     * @Route("/content_block/load", name="spliced_cms_admin_json_content_block_load")
     */
    public function contentBlocksLoadAction()
    {
        $response = new JsonResponse();
        $blocks = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:ContentBlock')->findAllBySiteForJson($this->get('spliced_cms.site_manager')->getCurrentAdminSite());
        return new JsonResponse(array(
            'success' => true,
            'data' => $blocks
        ));
    }

    /**
     * @Route("/menu/load", name="spliced_cms_admin_json_menu_load")
     */
    public function menusLoadAction()
    {
        $response = new JsonResponse();
        $menus = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Menu')
            ->findAllBySiteForJson($this->get('spliced_cms.site_manager')->getCurrentAdminSite());
        return new JsonResponse(array(
            'success' => true,
            'data' => $menus
        ));
    }

    /**
     * @Route("/template_extension/load", name="spliced_cms_admin_json_template_extension_load")
     */
    public function templateExtensionsLoad()
    {
        $response = new JsonResponse();
        $extensions = array();
        foreach ($this->get('spliced_cms.template_manager')->getExtensions() as $extension) {
            $extensions[$extension->getKey()] = array(
                'key' => $extensions->getKey(),
                'name' => $extension->getName(),
                'description' => $extension->getDescription(),
                'version' => $extension->getVersion()
            );
        }
        return new JsonResponse(array(
            'success' => true,
            'data' => array_values($extensions)
        ));
    }
    
    /**
     * @Route("/layout/load/{id}", name="spliced_cms_admin_json_layout_load_id")
     */
    public function layoutLoadAction($id)
    {
        $response = new JsonResponse();
        $layout = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Layout')->findOneByIdForJson($id);
        return new JsonResponse(array(
            'success' => true,
            'data' => $layout
        ));
    }
}

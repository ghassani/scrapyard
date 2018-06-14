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
use Spliced\Bundle\CmsBundle\Entity\Site;
use Spliced\Bundle\CmsBundle\Form\Type\SiteSelectionFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class SiteContextController extends Controller
{

    /**
     * @Route("/%spliced_cms.admin_route_prefix%/select_site", name="spliced_cms_admin_site_select")
     * @Template()
     */
    public function selectAction(Request $request)
    {

    }

    /**
     * @Route("/%spliced_cms.admin_route_prefix%/switch_site", name="spliced_cms_admin_switch_site")
     * @Template()
     */
    public function switchAction(Request $request)
    {
        $form = $this->createForm(new SiteSelectionFormType());
        
        if ($form->submit($request) && $form->isValid()) {
            
            $formData = $form->getData();

            if ($formData['site']) {
                $this->get('spliced_cms.site_manager')->setCurrentAdminSite($formData['site']);
                $this->get('session')->getFlashBag()->add('success', 'Site Switched To '. $formData['site']->getDomain());
            } else {
                $this->get('spliced_cms.site_manager')->setCurrentAdminSite(null);
                $this->get('session')->getFlashBag()->add('success', 'Site Switched To All');
            }
            
        }

        if ($request->server->get('HTTP_REFERER')) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        return $this->redirect($this->generateUrl('spliced_cms_admin_dashboard'));
    }

    /**
     * @Route("/%spliced_cms.admin_route_prefix%/switch_site/{id}", name="spliced_cms_admin_switch_site_id")
     * @Template()
     */
    public function switchByIdAction(Request $request, $id)
    {
        $site = $this->get('spliced_cms.site_manager')->findSiteById($id);

        if ($site) {
            $this->get('spliced_cms.site_manager')->setCurrentAdminSite($site);
            $this->get('session')->getFlashBag()->add('success', 'Site Switched To '. $site->getDomain());
        } else {
            $this->get('spliced_cms.site_manager')->setCurrentAdminSite(null);
            $this->get('session')->getFlashBag()->add('success', 'Site Switched To All');
        }

        if ($request->server->get('HTTP_REFERER')) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        return $this->redirect($this->generateUrl('spliced_cms_admin_dashboard'));
    }
}

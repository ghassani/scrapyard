<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spliced\Bundle\CmsBundle\Entity\ContentPage;
use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\TemplateRenderEvent;

/**
 * @Route("/page")
 */
class ContentPageController extends Controller
{
    /**
     * @Route("/{slug}", name="spliced_cms_content_page_by_slug", requirements={ "slug" : "(.)*" })
     */
    public function viewAction(Request $request, $slug)
    {
        $contentPage = $this->getDoctrine()
            ->getManager()
            ->getRepository('SplicedCmsBundle:ContentPage')
            ->findOneBySlug($slug);

        if (!$contentPage) {
            throw $this->createNotFoundException('Page Not Found');
        }

        return $this->renderContentPage($request, $contentPage);
    }

    /**
     * viewByIdAction
     *
     * @param int $id
     */
    public function viewByIdAction(Request $request, $id)
    {
        $contentPage = $this->getDoctrine()
            ->getManager()
            ->getRepository('SplicedCmsBundle:ContentPage')
            ->findOneById($id);

        if (!$contentPage) {
            throw $this->createNotFoundException('Page Not Found');
        }

        return $this->renderContentPage($request, $contentPage);
    }
    
    protected function renderContentPage(Request $request, ContentPage $contentPage)
    {
        $event = $this->get('event_dispatcher')->dispatch(
            Event::TEMPLATE_RENDER,
            new TemplateRenderEvent(
                $request,
                $contentPage->getTemplate(),
                $contentPage->getSite(),
                $contentPage->getLayout() ? $contentPage->getLayout()->getTemplate() : null,
                array(
                    'contentPage' => $contentPage
                )
            )
        );
        return $event->getResponse();
    }
}

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
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="spliced_cms_homepage")
    */
    public function indexAction()
    {

        $site = $this->get('spliced_cms.site_manager')
            ->getCurrentSite();

        if ($site && $site->getDefaultPage()) {

            return $this->forward('SplicedCmsBundle:ContentPage:viewById', array(
                'id'  => $site->getDefaultPage()->getId(),
            ));
        }

        throw $this->createNotFoundException('Page Not Found');
    }

}

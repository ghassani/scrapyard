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
use Symfony\Component\Finder\Finder;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/template")
 */
class TemplateController extends Controller
{
    /**
     * @Route("/", name="spliced_cms_admin_template_list")
     * @Template()
     */
    public function listAction()
    {
        $finder = new Finder();
        
        $finder->files()->in($this->container->getParameter('spliced_cms.template_dir'));
        
        return array(
            'templates' => $finder
        );
    }
}

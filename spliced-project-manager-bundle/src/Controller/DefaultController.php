<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="spliced_pms_dashboard")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}

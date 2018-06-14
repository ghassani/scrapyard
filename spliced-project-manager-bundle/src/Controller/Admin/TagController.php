<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Helper;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;

class TagController extends Controller
{
    /**
     * @Route("/tags", name="spliced_pms_tag_list")
     * @Template()
     */
    public function listAction()
    {
		$query = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Tag')
		  ->getListQuery();
		
		$pagination = $this->get('knp_paginator')->paginate(
		    $query,
		    $this->get('request')->query->get('page', 1)/*page number*/,
		    20/*limit per page*/
		);
		
        return array('pagination' => $pagination);
    }

    /**
     * @Route("/tags/view/{id}", name="spliced_pms_tag_view")
     * @Template()
     */
    public function viewAction($id){
 
	}
	
    /**
     * @Route("/tags/edit/{id}", name="spliced_pms_tag_edit")
     * @Template()
     */
    public function editAction($id)
    {
	}
	
    /**
     * @Route("/tags/delete/{id}", name="spliced_pms_tag_delete")
     * @Template()
	 * @Method({"POST"})
     */
    public function deleteAction($id)
    {
	}
	
    /**
     * @Route("/tags/batch_action", name="spliced_pms_tag_batch_action")
     * @Template()
     */
    public function batchActionAction($id)
    {
	}
}

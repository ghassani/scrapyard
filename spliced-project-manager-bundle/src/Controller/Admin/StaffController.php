<?php

namespace Spliced\Bundle\ProjectManagerBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spliced\Bundle\ProjectManagerBundle\Helper;
use Spliced\Bundle\ProjectManagerBundle\Form\Type as Forms;

class StaffController extends Controller
{
    /**
     * @Route("/staff", name="spliced_pms_staff_list")
     * @Template()
     */
    public function listAction()
    {
		$query = $this->getDoctrine()->getRepository('SplicedProjectManagerBundle:Staff')
		  ->getListQuery();
		
		$pagination = $this->get('knp_paginator')->paginate(
		    $query,
		    $this->get('request')->query->get('page', 1)/*page number*/,
		    20/*limit per page*/
		);

        return array('pagination' => $pagination);
    }

    /**
     * @Route("/staff/new", name="spliced_pms_staff_new")
     * @Template()
     */
    public function newAction(){

    }

    /**
     * @Route("/staff/save", name="spliced_pms_staff_save")
     * @Template()
     */
    public function saveAction(){

    }

    /**
     * @Route("/staff/view/{id}", name="spliced_pms_staff_view")
     * @Template()
     */
    public function viewAction($id){
 
	}
	
    /**
     * @Route("/staff/edit/{id}", name="spliced_pms_staff_edit")
     * @Template()
     */
    public function editAction($id)
    {
	}
	
    /**
     * @Route("/staff/delete/{id}", name="spliced_pms_staff_delete")
     * @Template()
	 * @Method({"POST"})
     */
    public function deleteAction($id)
    {
	}
	
    /**
     * @Route("/staff/batch_action", name="spliced_pms_staff_batch_action")
     * @Template()
     */
    public function batchActionAction($id)
    {
	}
}

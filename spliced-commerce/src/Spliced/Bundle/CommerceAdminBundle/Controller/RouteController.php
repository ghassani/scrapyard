<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Entity\Route as RouteEntity;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\RouteType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\RouteFilterType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Route controller.
 *
 * @Route("/route")
 */
class RouteController extends BaseFilterableController
{
    const FILTER_TAG = 'commerce.route';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\RouteFilterType';

    /**
     * Lists all Route entities.
     *
     * @Route("/", name="commerce_admin_route")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {        
        // load routes
        $routes = $this->get('knp_paginator')->paginate(
            $this->get('commerce.admin.entity_manager')
                ->getRepository('SplicedCommerceAdminBundle:Route')
                ->getAdminListQuery($this->getFilters()),
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit', 25)
        );
        
        $filterForm = $this->createForm(new RouteFilterType());

        return array(
            'routes' => $routes,
            'filterForm' => $filterForm->createView(),
        );
    }
    
    
    /**
     * Displays a form to create a new Route entity.
     *
     * @Route("/new", name="commerce_admin_route_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new RouteEntity();
        $form   = $this->createForm(new RouteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Creates a new Route entity.
     *
     * @Route("/", name="commerce_admin_route_save")
     * @Method("POST")
     * @Template("SplicedCommerceAdminBundle:Route:new.html.twig")
     */
    public function saveAction()
    {
        $entity  = new RouteEntity();
        $form = $this->createForm(new RouteType(), $entity);
        $form->bind($request);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
    
            return $this->redirect($this->generateUrl('route'));
        }
    
        return array(
                'entity' => $entity,
                'form'   => $form->createView(),
        );
    }
    
    /**
     * Displays a form to edit an existing Route entity.
     *
     * @Route("/{id}/edit", name="commerce_admin_route_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        try{
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Route')->findOneById($id);
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Route.');
        }

        $editForm = $this->createForm(new RouteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Route entity.
     *
     * @Route("/{id}", name="commerce_admin_route_update")
     * @Method("PUT")
     * @Template("SplicedCommerceAdminBundle:Route:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SplicedCommerceAdminBundle:Route')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Route entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RouteType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('route_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Route entity.
     *
     * @Route("/{id}", name="commerce_admin_route_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Route')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Route entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('route'));
    }

    /**
     * Creates a form to delete a Route entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Filters the list of entities for Route entity.
     *
     * @Route("/filter", name="commerce_admin_route_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $form   = $this->createForm(new RouteFilterType());
        $filters = array();
        
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
            
            $session->set('filter.route', serialize($filters));
        }
        

        $this->get('session')->getFlashBag()->add('notice', 'Route Filters Updated');
        return $this->redirect($this->generateUrl('commerce_admin_route'));
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_route_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        $this->get('session')->setFilters($filters);
        $this->get('session')->getFlashBag()->add('notice', 'Route Filters Cleared');
        return $this->redirect($this->generateUrl('commerce_admin_route'));
    }
    
    
    /**
     * Deletes a Route entity.
     *
     * @Route("/batch", name="commerce_admin_route_batch")
     * @Method("POST")
     */
    public function batchAction()
    {
        $ids = $this->getRequest()->request->get('ids');
        $action = $this->getRequest()->request->get('action');
        $methodName = 'batch'.ucwords($action);

        if(method_exists($this,$methodName)) {
            return call_user_func($this, $methodName, $ids);
        }
        
        throw new \InvalidArgumentException(sprintf('Method %s does not exist',$methodName));
    }
    

    /**
    * batchDelete
    * 
    * @param array $ids
    */
    protected function batchDelete(array $ids)
    {
        $entities = $em->getRepository('SplicedCommerceAdminBundle:Route')->findById($id);
        
        $count = count($entities);
        
        foreach($entities as $entity) {
            $em->remove($entity);
        }
        
        try{
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', sprintf('Successfully deleted %s records.', $count));
        } catch( \Exception $e) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Error deleting Records. Error: %s', $e->getMessage()));
        }
        
        return $this->redirect($this->generateUrl('route'));
    }
    
    
    /**
     * Check Route
     *
     * @Route("/check-route", name="commerce_admin_route_check")
     * @Template()
     */
    public function checkRouteAction()
    {
        if(!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Invalid Request Type');
        } else if(!$this->getRequest()->request->has('path')){
            throw new \InvalidArgumentException('Path POST variable required to check route');
        }

        $requestPath = $this->getRequest()->request->get('path');
        
        if(!preg_match('/^\//', $requestPath)){
            $requestPath = '/'.$requestPath;
        }
        
        $route = $this->get('commerce.admin.entity_manager')
          ->getRepository('SplicedCommerceAdminBundle:Route')
          ->findOneByRequestPath($requestPath);
            
        if(!$route){
            return new JsonResponse(array(
                'success' => true,
                'message' => 'Route Does Not Exist',
            ));
        }
        
        return new JsonResponse(array(
            'success' => false,
            'message' => 'Route Exists',
            'id' => $route->getId(),
            'request_path' => $route->getRequestPath(),
            'target_path' => $route->getTargetPath(),
        ));
    }

}

<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\NoResultException;
use Spliced\Bundle\CommerceAdminBundle\Entity\Visitor;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\VisitorType;
use Spliced\Bundle\CommerceAdminBundle\Form\Type\VisitorFilterType;
use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;

/**
 * Visitor controller.
 *
 * @Route("/visitor")
 */
class VisitorController extends BaseFilterableController
{

    const FILTER_TAG = 'commerce.visitor';
    const FILTER_FORM = 'Spliced\Bundle\CommerceAdminBundle\Form\Type\VisitorFilterType';
    
    /**
     * Lists all Visitor entities.
     *
     * @Route("/", name="commerce_admin_visitor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
    
        $query = $em->getRepository('SplicedCommerceAdminBundle:Visitor')->getAdminListQuery($this->getFilters('bots'), false)
          ->andWhere('visitor.isBot != 1')
          ->getQuery();
        
        $visitors = $this->get('knp_paginator')->paginate(
            $query,
            $this->getRequest()->query->get('page',1),
            $this->getRequest()->query->get('limit',25)
        );
        
        $filterForm = $this->createForm(new VisitorFilterType());
        
        return array(
            'visitors' => $visitors,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
     *
     * @Route("/bots", name="commerce_admin_visitor_bots")
     * @Method("GET")
     * @Template()
     */
    public function botsAction()
    {
        $em = $this->getDoctrine()->getManager();
    
        $query = $em->getRepository('SplicedCommerceAdminBundle:Visitor')->getAdminListQuery($this->getFilters('bots'), false)
          ->andWhere('visitor.isBot = 1')
          ->getQuery();
        
        $visitors = $this->get('knp_paginator')->paginate(
            $query,
            $this->getRequest()->query->get('page', 1),
            $this->getRequest()->query->get('limit', 25)
        );
    
        $filterForm = $this->createForm(new VisitorFilterType());
    
        return array(
            'visitors' => $visitors,
            'filterForm' => $filterForm->createView(),
        );
    }
    
    /**
     * View Visitor Browser Information for Given Timeframe
     *
     * @Route("/browsers", name="commerce_admin_visitor_browsers")
     * @Template()
     */
    public function browsersAction()
    {
        $em = $this->getDoctrine()->getManager();
    
        $from = new \DateTime(sprintf('first day of %s %s', date('F'), date('Y')));
        $to = new \DateTime(sprintf('last day of %s %s', date('F'), date('Y')));
        
        $baseCountQuery = $em->createQueryBuilder()
        ->select('count(visitor.userAgent), visitor.userAgent')
        ->from('SplicedCommerceAdminBundle:Visitor', 'visitor')
        ->where('visitor.createdAt BETWEEN :dayStart AND :dayEnd')
        ->groupBy('request.createdAt')
        ->setParameter('dayStart', $from->format('Y-m-d'))
        ->setParameter('dayEnd', $to->format('Y-m-d'))
        ->groupBy('visitor.userAgent');
         
        $count = $baseCountQuery->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $browsers = array();
        foreach($count as $row){
            $browserData = UserAgentHelper::parseUserAgent($row['userAgent']);
            
            if(empty($browserData['browser'])){
                $browserData['browser'] = 'Other/Bot';
            }
            
            $hasBrowser = false;
            $hasVersion = false;
            $browser = $browserData['browser'];
            $version = $browserData['version'];
            
            $label = $browserData['browser'].' '.$browserData['version'];
            foreach($browsers as &$dataSet){
                if(isset($dataSet[$browser])){
                    $hasBrowser = true;
                    $dataSet[$browser]['count'] += $row[1];
                    if(isset($dataSet[$browser]['versions'][$version])){
                        $dataSet[$browser]['versions'][$version] += $row[1];
                    } else if(!isset($dataSet[$browser]['versions'])) {
                        $dataSet[$browser]['versions'] = array($version => $row[1]);
                    } else {
                        $dataSet[$browser]['versions'][$version] = $row[1];
                    }
                    break;
                }
            }
            
            if(!$hasBrowser){
                $browsers[$browser] = $row[1];
            }
        }        
        
        arsort($browsers);
        
        return array(
            'from' => $from,
            'to' => $to,
            'browsers' => $browsers,
        );
    }
    
    /**
     *
     * @Route("/view/{id}", name="commerce_admin_visitor_view")
     * @Route("/edit/{id}", name="commerce_admin_visitor_edit")
     * @Method("GET")
     * @Template()
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        try{
            $visitor = $em->getRepository('SplicedCommerceAdminBundle:Visitor')->findOneByIdForView($id);
        } catch(NoResultException $e) {
            throw $this->createNotFoundException('Unable to find Visitor.');
        }
        
        return array('visitor' => $visitor);
    }
    
    /**
     * Deletes a Visitor entity.
     *
     * @Route("/{id}", name="commerce_admin_visitor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SplicedCommerceAdminBundle:Visitor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Visitor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('order'));
    }

    /**
     * Creates a form to delete a Visitor entity by id.
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
     * Filters the list of entities for Visitor entity.
     *
     * @Route("/filter", name="commerce_admin_visitor_filter")
     * @Method("POST")
     * @Template()
     */
    public function filterAction()
    {
        parent::filterAction();
    }


    /**
     * Clears the currently applied filters
     * @Route("/filter/reset", name="commerce_admin_visitor_filter_reset")
     * @return array
     */
    public function clearFiltersActions()
    {
        parent::cleanFiltersAction();
    }
    
    
    /**
     *
     * @Route("/batch", name="commerce_admin_visitor_batch")
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
    

    
}

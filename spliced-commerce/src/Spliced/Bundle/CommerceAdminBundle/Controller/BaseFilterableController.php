<?php

namespace Spliced\Bundle\CommerceAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\CommerceAdminBundle\Model\ListFilter;

/**
 * BaseFilterableController
 *
*/
class BaseFilterableController extends Controller
{
   
    /**
     * 
     */
    public function filterAction()
    {
        $session = $this->get('session');
        $className = static::FILTER_FORM;
        $form   = $this->createForm(new $className());
        $filters = array();
    
        if($form->bindRequest($this->getRequest()) && $form->isValid()) {
            $filters = $form->getData();
            
            if($this->getRequest()->request->has('tag')){
                $session->set(static::FILTER_TAG.'.'.$this->getRequest()->request->get('tag'), serialize($filters));
            } else {
                $session->set(static::FILTER_TAG, serialize($filters));
            }
            
            $this->get('session')->getFlashBag()->add('success', 'Filters Updated');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Filter Form Was Invalid. Filters Not Updated');
        }
    }
    
    
    /**
     * 
     */
    public function clearFiltersActions()
    {
        $filters = new ListFilter();
        if($this->getRequest()->request->has('tag')){
            $this->get('session')->set(static::FILTER_TAG.'.'.$this->getRequest()->request->get('tag'), serialize($filters));
        } else {
            $this->get('session')->set(static::FILTER_TAG, serialize($filters));
        }
        $this->get('session')->getFlashBag()->add('notice', 'Filters Cleared');
    }
    
    
    /**
     * 
     */
    protected function getFilters($tag = null)
    {
        if(!is_null($tag)){
            return $this->get('session')->has(static::FILTER_TAG.'.'.$tag) ?
            unserialize($this->get('session')->get(static::FILTER_TAG.'.'.$tag)) : new ListFilter();
        }
    
        return $this->get('session')->has(static::FILTER_TAG) ?
        unserialize($this->get('session')->get(static::FILTER_TAG)) : new ListFilter();
    
    }
     
    /**
     * 
     */
    protected function setFilters(ListFilter $filters, $tag = null)
    {
        if(!is_null($tag)){
            $this->get('session')->set(static::FILTER_TAG.'.'.$tag, $filters->serialize());
            return $this;
        }
         
        $this->get('session')->set(static::FILTER_TAG, $filters->serialize());
        return $this;
    }
}

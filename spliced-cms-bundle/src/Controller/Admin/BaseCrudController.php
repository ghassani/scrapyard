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

use Imagine\Exception\InvalidArgumentException;
use Spliced\Bundle\CmsBundle\Model\ListFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spliced\Bundle\CmsBundle\Form\Type\BatchActionFormType;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseCrudController extends Controller
{
    /**
     * @return string
     */
    abstract protected function getSessionKey();

    /**
     * @return string
     */
    abstract protected function getFilterForm();

    /**
     * @return string
     */
    abstract protected function getCrudClass();

    /**
     * @param array $options
     * @return mixed
     */
    abstract protected function getBatchRedirect();

    /**
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $form = $this->getFilterForm();
        
        if ($form->submit($request) && $form->isValid()) {
            $this->setFilters($form->getData());
            $this->get('session')->getFlashBag()->add('success', 'Filters Updated');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Error Validating Filter Input');
        }

        return $this->redirect($this->getBatchRedirect());
    }

    public function resetFilterAction(Request $request)
    {
        $this->setFilters(array());
        $this->get('session')->getFlashBag()->add('success', 'Filters Reset');
        return $this->redirect($this->getBatchRedirect());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function batchAction(Request $request)
    {
        $form = $this->createBatchActionForm();
        
        if (!$form->submit($request) || !$form->isValid()) {
            $this->get('session')->getFlashBag()->add('error', 'Invalid Submission');
            return $this->redirect($this->getBatchRedirect());
        }

        $formData = $form->getData();
        
        if (!$request->request->has('ids')) {
            $this->get('session')->getFlashBag()->add('info', 'Nothing Selected');
            return $this->redirect($this->getBatchRedirect());
        }

        if (!method_exists($this, 'batch'.ucwords($formData['action']))) {
            $this->get('session')->getFlashBag()->add('error', sprintf('Unknown Batch Action `%s`', $formData['action']));
            return $this->redirect($this->getBatchRedirect());
        }

        $entities = $this->get('doctrine.orm.entity_manager')
            ->getRepository($this->getCrudClass())
            ->findBy(array(
                'id' => $request->request->get('ids')
            ));

        return call_user_func(array($this, 'batch'.ucwords($formData['action'])), $request, $entities);
    }

    /**
     * @param $object
     * @return Form
     */
    protected function createDeleteForm($object)
    {
        return $this->createFormBuilder($object)
            ->add('delete', 'submit')
            ->getForm();
    }

    /**
     * @param array $data
     * @return Form
     */
    protected function createBatchActionForm(array $data = array())
    {
        return $this->createForm(new BatchActionFormType(), $data);
    }

    /**
     * @return ListFilter
     */
    protected function getFilters()
    {
       $filters = $this->get('session')->get($this->getSessionKey(), array());
        if (is_string($filters)) {
            $filters = unserialize($filters);
        }
        
        if (!is_array($filters)) {
            $filters = array();
        }

        return new ListFilter($filters);
    }
    
    /**
     * @param $filters
     * @return $this
     * @throws \Imagine\Exception\InvalidArgumentException
     */
    protected function setFilters($filters)
    {
        if (!is_array($filters) && !$filters instanceof ListFilter) {
            throw new InvalidArgumentException('setFilters takes an array or a ListFilter object');
        }

        $this->get('session')->set($this->getSessionKey(), $filters instanceof ListFilter ? $filters->serialize() : $filters);
        
        return $this;
    }
}
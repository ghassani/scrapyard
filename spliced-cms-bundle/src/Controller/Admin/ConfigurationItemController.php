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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Spliced\Bundle\ConfigurationBundle\Form\Type\ConfigurationItemFilterFormType;
use Spliced\Bundle\ConfigurationBundle\Form\Type\ConfigurationItemFormType;
use Spliced\Bundle\ConfigurationBundle\Model\ConfigurationItemInterface;
use Spliced\Bundle\ConfigurationBundle\Model\ListFilter;
use Spliced\Bundle\ConfigurationBundle\Entity\Configuration;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/configuration")
 */
class ConfigurationItemController extends BaseCrudController
{
    /**
     * @Route("/", name="spliced_cms_admin_configuration_item_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $itemsQuery = $this->getDoctrine()
            ->getRepository('SplicedConfigurationBundle:Configuration')
            ->getFilteredQuery($this->getFilters());
        $items = $this->get('knp_paginator')->paginate(
            $itemsQuery,
            $this->get('request')->query->get('page', 1),
            25
        );
        return array(
            'items' => $items,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
        );
    }

    /**
     * @Route("/item/new", name="spliced_cms_admin_configuration_item_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $form = $this->createConfigurationItemForm();
        if ($request->getMethod() == 'POST') {
            $form->submit($request);
            $form = $this->createConfigurationItemForm($form->getData());
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/item/save", name="spliced_cms_admin_configuration_item_save")
     * @Template("SplicedCmsBundle:Admin/ConfigurationItem:new.html.twig")
     */
    public function saveAction(Request $request)
    {
        $form = $this->createConfigurationItemForm()->submit($request);
        // rebuild to get type
        $form = $this->createConfigurationItemForm($form->getData());
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $item = $form->getData();
            try {
                $this->get('spliced.configuration_manager')->save($item);
                $this->get('session')->getFlashBag()->add('success', 'Configuration Item Successfully Created');
                return $this->redirect($this->generateUrl('spliced_cms_admin_configuration_item_edit', array(
                    'id' => $item->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Saving Configuration Item');
                return array(
                    'form' => $form->createView()
                );
            }
        }
        $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your input');
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/edit/{id}", name="spliced_cms_admin_configuration_item_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $configurationItem = $this->getDoctrine()
            ->getRepository('SplicedConfigurationBundle:Configuration')
            ->findOneById($id);
        if (!$configurationItem) {
            return $this->createNotFoundException('Configuration Item Not Found');
        }
        $form = $this->createConfigurationItemForm($configurationItem);
        return array(
            'configurationItem' => $configurationItem,
            'form' => $form->createView(),
            'deleteForm' => $this->createDeleteForm($configurationItem)->createView(),
        );
    }

    /**
     * @Route("/item/update/{id}", name="spliced_cms_admin_configuration_item_update")
     * @Template("SplicedCmsBundle:Admin/ConfigurationItem:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $configurationItem = $this->getDoctrine()
            ->getRepository('SplicedConfigurationBundle:Configuration')
            ->findOneById($id);
        
        if (!$configurationItem) {
            return $this->createNotFoundException('Configuration Item Not Found');
        }
        
        $form = $this->createConfigurationItemForm($configurationItem);
        
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $item = $form->getData();
            
            try {
                $this->get('spliced.configuration_manager')->update($item);
                $this->get('session')->getFlashBag()->add('success', 'Configuration Item Successfully Updated');
                return $this->redirect($this->generateUrl('spliced_cms_admin_configuration_item_edit', array(
                    'id' => $item->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Updating Configuration Item');
            }

        } else {
            $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your input');
        }

        return array(
            'configurationItem' => $configurationItem,
            'form' => $form->createView(),
            'deleteForm' => $this->createDeleteForm($configurationItem)->createView(),
        );
    }

    /**
     * @Route("/filter", name="spliced_cms_admin_configuration_item_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }

    /**
     * @Route("/filter/reset", name="spliced_cms_admin_configuration_item_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }

    /**
     * @Route("/batch", name="spliced_cms_admin_configuration_item_batch")
     * @Method("POST")
     */
    public function batchAction(Request $request)
    {
        return parent::batchAction($request);
    }

    /**
     * batchDelete
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchDelete(Request $request, $items)
    {
        $deleted = 0;
        foreach ($items as $item) {
            try {
                $this->get('spliced.configuration_manager')->delete($item);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Configuration Items', $deleted, count($items)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_configuration_item_list'));
    }

    protected function getSessionKey()
    {
        return 'spliced_configuration_filter';
    }

    protected function getFilterForm()
    {
        return $this->createForm(new ConfigurationItemFilterFormType(), $this->getFilters());
    }

    /**
     * @return string
     */
    protected function getCrudClass()
    {
        return 'SplicedConfigurationBundle:Configuration';
    }

    /**
     * @param array $options
     * @return mixed
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_configuration_list');
    }
  
    protected function createConfigurationItemForm(ConfigurationItemInterface $item = null, array $options = array())
    {
        if (is_null($item)) {
            $item = new Configuration();
        }
        return $this->createForm(new ConfigurationItemFormType($this->get('spliced.configuration_manager'), $item), $item, $options);
    }
    
    /**
     * @return ListFilter
     */
    protected function getFilters()
    {
        return new ListFilter((array) parent::getFilters());
    }
} 
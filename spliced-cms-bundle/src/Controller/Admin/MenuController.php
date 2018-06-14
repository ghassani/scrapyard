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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Spliced\Bundle\CmsBundle\Entity\Menu;
use Spliced\Bundle\CmsBundle\Form\Type\MenuFormType;
use Spliced\Bundle\CmsBundle\Form\Type\MenuFilterFormType;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/menu")
 */
class MenuController extends BaseCrudController
{
    /**
     * @Route("/", name="spliced_cms_admin_menu_list")
     * @Template()
     */
    public function listAction()
    {
        $menusQuery = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Menu')
            ->getFilteredQuery($this->getFilters());
        $site = $this->get('spliced_cms.site_manager')->getCurrentAdminSite();
        if ($site) {
            $menusQuery->where('m.site = :site')
                ->setParameter('site', $site->getId());
        }
        $menus = $this->get('knp_paginator')->paginate(
            $menusQuery,
            $this->get('request')->query->get('page', 1),
            25
        );
        return array(
            'menus' => $menus,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
        );
    }
    /**
     * @Route("/new", name="spliced_cms_admin_menu_new")
     * @Template()
     */
    public function newAction()
    {
        return array(
            'form' => $this->createMenuForm()->createView()
        );
    }
    /**
     * @Route("/save", name="spliced_cms_admin_menu_save")
     * @Template("SplicedCmsBundle:Admin/Menu:new.html.twig")
     */
    public function saveAction()
    {
        $siteManager = $this->get('spliced_cms.site_manager');
        $form = $this->createMenuForm();
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $menu = $form->getData();
            if(!$menu->getSite()) {
                $menu->setSite($siteManager->getCurrentAdminSite());
            }
            try {
                $this->get('spliced_cms.menu_manager')->save($menu);
                $this->get('session')->getFlashBag()->add('success', 'Menu Successfully Created');
                return $this->redirect($this->generateUrl('spliced_cms_admin_menu_edit', array(
                    'id' => $menu->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Saving Menu');
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
     * @Route("/edit/{id}", name="spliced_cms_admin_menu_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $menu = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Menu')
            ->findOneById($id);
        if (!$menu) {
            return $this->createNotFoundException('Menu Not Found');
        }
        $form = $this->createMenuForm($menu);
        return array(
            'menu' => $menu,
            'form' => $form->createView()
        );
    }
    /**
     * @Route("/filter", name="spliced_cms_admin_menu_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }
    /**
     * @Route("/filter/reset", name="spliced_cms_admin_menu_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }
    /**
     * @Route("/batch", name="spliced_cms_admin_menu_batch")
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
     * @param $menus
     */
    public function batchDelete(Request $request, $menus)
    {
        $deleted = 0;
        
        foreach ($menus as $menu) {
            try {
                $this->get('spliced_cms.menu_manager')->delete($menu);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Menus', $deleted, count($menus)));

        return $this->redirect($this->generateUrl('spliced_cms_admin_menu_list'));
    }
    /**
     * batchActivate
     *
     * @param Request $request
     * @param $menus
     */
    public function batchActivate(Request $request, $menus)
    {
        $updated = 0;
        foreach ($menus as $menu) {
            try {
                $menu->setIsActive(true);
                $this->get('spliced_cms.menu_manager')->update($menu);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Activated %s/%s Menus', $updated, count($menus)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_menu_list'));
    }
    /**
     * batchDeactivate
     *
     * @param Request $request
     * @param $menus
     */
    public function batchDeactivate(Request $request, $menus)
    {
        $updated = 0;
        foreach ($menus as $menu) {
            try {
                $menu->setIsActive(false);
                $this->get('spliced_cms.menu_manager')->update($menu);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deactivated %s/%s Menus', $updated, count($menus)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_menu_list'));
    }
    
    /**
     * @Route("/update/{id}", name="spliced_cms_admin_menu_update")
     * @Template("SplicedCmsBundle:Admin/Menu:edit.html.twig")
     */
    public function updateAction($id)
    {
        $menu = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Menu')
            ->findOneById($id);
        if (!$menu) {
            return $this->createNotFoundException('Menu Not Found');
        }
        $form = $this->createMenuForm($menu);
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $menu = $form->getData();
            try {
                $this->get('spliced_cms.menu_manager')->update($menu);
                $this->get('session')->getFlashBag()->add('success', 'Menu Successfully Updated');
                return $this->redirect($this->generateUrl('spliced_cms_admin_menu_edit', array(
                    'id' => $menu->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Updating Menu');
                return array(
                    'menu' => $menu,
                    'form' => $form->createView()
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'You submission contains invalid data');
        }
        return array(
            'menu' => $menu,
            'form' => $form->createView()
        );
    }
    /**
     * @param Layout $layout
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createMenuForm(Menu $menu = null, array $options = array())
    {
        if (is_null($menu)) {
            $menu = new Menu();
        }
        return $this->createForm(new MenuFormType($menu), $menu, $options);
    }
    /**
     * {@inheritDoc}
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_layout_list');
    }
    /**
     * {@inheritDoc}
     */
    protected function getCrudClass()
    {
        return 'SplicedCmsBundle:Menu';
    }
    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
        return $this->createForm(new MenuFilterFormType(), $this->getFilters());
    }
    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_menu_filter';
    }
}

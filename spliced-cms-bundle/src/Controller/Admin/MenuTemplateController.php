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

use Spliced\Bundle\CmsBundle\Form\Type\MenuTemplateFilterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\CmsBundle\Form\Type\MenuTemplateFormType;
use Spliced\Bundle\CmsBundle\Entity\MenuTemplate;
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/menu_template")
 */
class MenuTemplateController extends BaseCrudController
{
    /**
     * @Route("/", name="spliced_cms_admin_menu_template_list")
     * @Template()
     */
    public function listAction()
    {
        $menuTemplatesQuery = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:MenuTemplate')
            ->getFilteredQuery($this->getFilters());
        $site = $this->get('spliced_cms.site_manager')->getCurrentAdminSite();
        if ($site) {
            $menuTemplatesQuery->where('m.site = :site')
                ->setParameter('site', $site->getId());
        }
        $menuTemplates = $this->get('knp_paginator')->paginate(
            $menuTemplatesQuery,
            $this->get('request')->query->get('page', 1),
            25
        );
        return array(
            'menuTemplates' => $menuTemplates,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
        );
    }
    /**
     * @Route("/new", name="spliced_cms_admin_menu_template_new")
     * @Template()
     */
    public function newAction()
    {
        return array(
            'form' => $this->createMenuTemplateForm()->createView()
        );
    }
    /**
     * @Route("/save", name="spliced_cms_admin_menu_template_save")
     * @Template("SplicedCmsBundle:Admin/Menu:new.html.twig")
     */
    public function saveAction()
    {
        $siteManager = $this->get('spliced_cms.site_manager');
        $form = $this->createMenuTemplateForm();
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $menuTemplate = $form->getData();
            if (!$menuTemplate->getSite()) {
                $menuTemplate->setSite($siteManager->getCurrentAdminSite());
            }
            $template = $menuTemplate->getTemplate();
            $templateVersion = $template->getVersion();
            $templateVersion->setTemplate($template);
            if (strlen(trim($templateVersion->getContent()))) {
                try {
                    $this->get('spliced_cms.menu_template_manager')->save($menuTemplate);
                    $this->get('session')->getFlashBag()->add('success', 'Menu Template Successfully Created');
                    return $this->redirect($this->generateUrl('spliced_cms_admin_menu_template_edit', array(
                        'id' => $menuTemplate->getId()
                    )));
                } catch (\Exception $e) {
                    if ($this->get('kernel')->getEnvironment() == 'dev') {
                        throw $e;
                    }
                    $this->get('session')->getFlashBag()->add('error', 'Error Saving Menu Template');
                    return array(
                        'form' => $form->createView()
                    );
                }
            }
        }
        $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your input');
        return array(
            'form' => $form->createView()
        );
    }
    /**
     * @Route("/edit/{id}", name="spliced_cms_admin_menu_template_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $menuTemplate = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:MenuTemplate')
            ->findOneById($id);
        if (!$menuTemplate) {
            return $this->createNotFoundException('Menu Template Not Found');
        }
        $form = $this->createMenuTemplateForm($menuTemplate);
        return array(
            'menuTemplate' => $menuTemplate,
            'form' => $form->createView()
        );
    }
    /**
     * @Route("/filter", name="spliced_cms_admin_menu_template_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }
    /**
     * @Route("/filter/reset", name="spliced_cms_admin_menu_template_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }
    /**
     * @Route("/batch", name="spliced_cms_admin_menu_template_batch")
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
     * @param $menuTemplates
     */
    public function batchDelete(Request $request, $menuTemplates)
    {
        $deleted = 0;
        foreach ($menuTemplates as $menuTemplate) {
            try {
                $this->get('spliced_cms.menu_template_manager')->delete($menuTemplate);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Menu Templates', $deleted, count($menuTemplates)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_menu_template_list'));
    }
    /**
     * batchPublish
     *
     * @param Request $request
     * @param $menuTemplates
     */
    public function batchPublish(Request $request, $menuTemplates)
    {
        $updated = 0;
        foreach ($menuTemplates as $menuTemplate) {
            try {
                $menuTemplate->getTemplate()->setActiveVersion($menuTemplate->getTemplate()->getVersion());
                $this->get('spliced_cms.menu_template_manager')->update($menuTemplate);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Published Changes to %s/%s Menu Templates', $updated, count($menuTemplates)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_menu_template_list'));
    }
    /**
     * @Route("/update/{id}", name="spliced_cms_admin_menu_template_update")
     * @Template("SplicedCmsBundle:Admin/Menu:edit.html.twig")
     */
    public function updateAction($id)
    {
        $menuTemplate = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:MenuTemplate')
            ->findOneById($id);
        if (!$menuTemplate) {
            return $this->createNotFoundException('Menu Template Not Found');
        }
        if (!$menuTemplate->getTemplate() || !$menuTemplate->getTemplate()->getVersion()) {
            throw new \RuntimeException(sprintf('Expected a Template and a Template Version'));
        }
        $originalContent = $menuTemplate
            ->getTemplate()
            ->getVersion()
            ->getContent();
        $form = $this->createMenuTemplateForm($menuTemplate);
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $menuTemplate = $form->getData();
            $userLabel = trim($form['template']['label']->getData());
            if (md5($originalContent) != md5(trim($menuTemplate->getTemplate()->getVersion()->getContent()))) {
                // save new version
                $templateVersion = new TemplateVersion();
                $templateVersion->setTemplate($menuTemplate->getTemplate())
                    ->setLabel($userLabel ? $userLabel : date('m/d/Y h:i:a'))
                    ->setContent(trim($menuTemplate->getTemplate()->getVersion()->getContent()));
                $menuTemplate->getTemplate()->setVersion($templateVersion);
            } else {
                if ($userLabel) {
                    $menuTemplate->getTemplate()->getVersion()->setLabel($userLabel);
                }
            }
            // call the template manager to save/update the record
            // this also flushes the EntityManager
            try {
                $this->get('spliced_cms.menu_template_manager')->update($menuTemplate);
                $this->get('session')->getFlashBag()->add('success', 'Menu Template Successfully Updated');
                return $this->redirect($this->generateUrl('spliced_cms_admin_menu_template_edit', array(
                    'id' => $menuTemplate->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Updating Menu Template');
                return array(
                    'menuTemplate' => $menuTemplate,
                    'form' => $form->createView()
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'You submission contains invalid data');
        }
        return array(
            'menuTemplate' => $menuTemplate,
            'form' => $form->createView()
        );
    }
    /**
     * @param MenuTemplate $menuTemplate
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createMenuTemplateForm(MenuTemplate $menuTemplate = null, array $options = array())
    {
        if (is_null($menuTemplate)) {
            $menuTemplate = new MenuTemplate();
        }
        return $this->createForm(new MenuTemplateFormType($menuTemplate), $menuTemplate, $options);
    }
    /**
     * {@inheritDoc}
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_menu_template_list');
    }
    /**
     * {@inheritDoc}
     */
    protected function getCrudClass()
    {
        return 'SplicedCmsBundle:MenuTemplate';
    }
    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
        return $this->createForm(new MenuTemplateFilterFormType(), $this->getFilters());
    }
    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_site_filter';
    }
}

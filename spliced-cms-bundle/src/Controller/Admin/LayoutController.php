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
use Spliced\Bundle\CmsBundle\Entity\Layout;
use Spliced\Bundle\CmsBundle\Entity\Template as CmsTemplate;
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;
use Spliced\Bundle\CmsBundle\Form\Type\LayoutFormType;
use Spliced\Bundle\CmsBundle\Form\Type\LayoutFilterFormType;
use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\LayoutEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/layout")
 */
class LayoutController extends BaseCrudController
{
    /**
     * @Route("/", name="spliced_cms_admin_layout_list")
     * @Template()
     */
    public function listAction()
    {
        $layoutsQuery = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Layout')
            ->getFilteredQuery($this->getFilters());
        $site = $this->get('spliced_cms.site_manager')->getCurrentAdminSite();
        if ($site) {
            $layoutsQuery->where('l.site = :site')
                ->setParameter('site', $site->getId());
        }
        $layouts = $this->get('knp_paginator')->paginate(
            $layoutsQuery,
            $this->get('request')->query->get('page', 1),
            25
        );
        return array(
            'layouts' => $layouts,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
        );
    }

    /**
     * @Route("/new", name="spliced_cms_admin_layout_new")
     * @Template()
     */
    public function newAction()
    {
        return array(
            'form' => $this->createLayoutForm()->createView()
        );
    }

    /**
     * @Route("/save", name="spliced_cms_admin_layout_save")
     * @Template("SplicedCmsBundle:Admin/Layout:new.html.twig")
     */
    public function saveAction()
    {
        $siteManager = $this->get('spliced_cms.site_manager');
        $form = $this->createLayoutForm();
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $layout = $form->getData();
            if(!$layout->getSite()) {
                $layout->setSite($siteManager->getCurrentAdminSite());
            }
            if (strlen(trim($layout->getTemplate()->getVersion()->getContent()))) {
                // make sure we have a file name set for the template
                $layout->getTemplate()->setFilename(sprintf('layout_%s_%s.html',
                    $layout->getSite()->getId(),
                    $layout->getLayoutKey()
                ));
                $this->getDoctrine()->getManager()->persist($layout);
                try {
                    $this->get('spliced_cms.layout_manager')->save($layout);
                    $this->get('session')->getFlashBag()->add('success', 'Layout Successfully Created');
                    return $this->redirect($this->generateUrl('spliced_cms_admin_layout_edit', array(
                        'id' => $layout->getId()
                    )));
                } catch (\Exception $e) {
                    if ($this->get('kernel')->getEnvironment() == 'dev') {
                        throw $e;
                    }
                    $this->get('session')->getFlashBag()->add('error', 'Error Saving Layout');
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
     * @Route("/edit/{id}", name="spliced_cms_admin_layout_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $layout = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Layout')
            ->findOneById($id);
        if (!$layout) {
            return $this->createNotFoundException('Layout Not Found');
        }
        $form = $this->createLayoutForm($layout);
        return array(
            'layout' => $layout,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/update/{id}", name="spliced_cms_admin_layout_update")
     * @Template("SplicedCmsBundle:Admin/Layout:edit.html.twig")
     */
    public function updateAction($id)
    {
        $layout = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Layout')
            ->findOneById($id);
        if (!$layout) {
            return $this->createNotFoundException('Layout Not Found');
        }
        if (!$layout->getTemplate() || !$layout->getTemplate()->getVersion()) {
            throw new \RuntimeException(sprintf('Expected a Template and a Template Version'));
        }
        $originalContent = $layout
            ->getTemplate()
            ->getVersion()
            ->getContent();
        $form = $this->createLayoutForm($layout);
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $layout = $form->getData();
            $userLabel = trim($form['template']['label']->getData());
            if (md5($originalContent) != md5(trim($layout->getTemplate()->getVersion()->getContent()))) {
                // save new version
                $templateVersion = new TemplateVersion();
                $templateVersion->setTemplate($layout->getTemplate())
                    ->setLabel($userLabel ? $userLabel : date('m/d/Y h:i:a'))
                    ->setContent(trim($layout->getTemplate()->getVersion()->getContent()));
                $layout->getTemplate()->setVersion($templateVersion);
            } else {
                if ($userLabel) {
                    $layout->getTemplate()->getVersion()->setLabel($userLabel);
                }
            }
            try {
                $this->get('spliced_cms.layout_manager')->update($layout);
                $this->get('session')->getFlashBag()->add('success', 'Layout Successfully Updated');
                return $this->redirect($this->generateUrl('spliced_cms_admin_layout_edit', array(
                    'id' => $layout->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Updating Layout');
                return array(
                    'layout' => $layout,
                    'form' => $form->createView()
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'You submission contains invalid data');
        }
        return array(
            'layout' => $layout,
            'form' => $form->createView()
        );
    }

    public function deleteAction($id)
    {
        $layout = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Layout')
            ->findOneById($id);
        if (!$layout) {
            return $this->createNotFoundException('Layout Not Found');
        }
        $form = $this->createFormBuilder($layout)
            ->add('delete', 'submit')
            ->getForm();
        $form->handleRequest($this->get('request'));
    }

    /**
     * @Route("/filter", name="spliced_cms_admin_layout_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }

    /**
     * @Route("/filter/reset", name="spliced_cms_admin_layout_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }

    /**
     * @Route("/batch", name="spliced_cms_admin_layout_batch")
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
     * @param $layouts
     */
    public function batchDelete(Request $request, $layouts)
    {
        $deleted = 0;
        foreach ($layouts as $layout) {
            try {
                $this->get('spliced_cms.layout_manager')->delete($layout);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Layouts', $deleted, count($layouts)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_layout_list'));
    }

    /**
     * batchPublish
     *
     * @param Request $request
     * @param $layouts
     */
    public function batchPublish(Request $request, $layouts)
    {
        $updated = 0;
        foreach ($layouts as $layout) {
            try {
                $layout->getTemplate()->setActiveVersion($layout->getTemplate()->getVersion());
                $this->get('spliced_cms.layout_manager')->update($layout);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Published Changes to %s/%s Layouts', $updated, count($layouts)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_layout_list'));
    }

    /**
     * @param Layout $layout
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createLayoutForm(Layout $layout = null, array $options = array())
    {
        if (is_null($layout)) {
            $layout = new Layout();
        }
        return $this->createForm(new LayoutFormType($this->get('spliced_cms.site_manager'), $layout), $layout, $options);
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
        return 'SplicedCmsBundle:Layout';
    }

    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_layout_filter';
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
        return $this->createForm(new LayoutFilterFormType(), $this->getFilters());
    }
}

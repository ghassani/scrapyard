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
use Symfony\Component\HttpFoundation\Response;
use Spliced\Bundle\CmsBundle\Entity\ContentBlock;
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;
use Spliced\Bundle\CmsBundle\Form\Type\ContentBlockFormType;
use Spliced\Bundle\CmsBundle\Form\Type\ContentBlockFilterFormType;
use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\ContentBlockEvent;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/content_block")
 */
class ContentBlockController extends BaseCrudController
{
    /**
     * @Route("/", name="spliced_cms_admin_content_block_list")
     * @Template()
     */
    public function listAction()
    {
        $contentBlocksQuery = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:ContentBlock')
            ->getFilteredQuery($this->getFilters());
        $site = $this->get('spliced_cms.site_manager')->getCurrentAdminSite();
        if ($site) {
            $contentBlocksQuery->where('b.site = :site')
                ->setParameter('site', $site->getId());
        }
        $contentBlocks = $this->get('knp_paginator')->paginate(
            $contentBlocksQuery,
            $this->get('request')->query->get('page', 1),
            25
        );
        return array(
            'contentBlocks' => $contentBlocks,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
        );
    }

    /**
     * @Route("/new", name="spliced_cms_admin_content_block_new")
     * @Template()
     */
    public function newAction()
    {
        return array(
            'form' => $this->createContentBlockForm()->createView()
        );
    }

    /**
     * @Route("/save", name="spliced_cms_admin_content_block_save")
     * @Template("SplicedCmsBundle:Admin/ContentBlock:new.html.twig")
     */
    public function saveAction()
    {
        $siteManager = $this->get('spliced_cms.site_manager');
        $form = $this->createContentBlockForm();
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $contentBlock = $form->getData();
            if(!$contentBlock->getSite()) {
                $contentBlock->setSite($siteManager->getCurrentAdminSite());
            }
            $content = trim($contentBlock->getTemplate()
                ->getVersion()
                ->getContent());
            if (strlen($content)) {
                try {
                    $this->get('spliced_cms.content_block_manager')->save($contentBlock);
                    $this->get('session')->getFlashBag()->add('success', 'Content Block Successfully Created');
                    return $this->redirect($this->generateUrl('spliced_cms_admin_content_block_edit', array(
                        'id' => $contentBlock->getId()
                    )));
                } catch (\Exception $e) {
                    if ($this->get('kernel')->getEnvironment() == 'dev') {
                        throw $e;
                    }
                    $this->get('session')->getFlashBag()->add('error', 'Error Saving Content Block');
                    return array(
                        'contentBlock' => $contentBlock,
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
     * @Route("/edit/{id}", name="spliced_cms_admin_content_block_edit")
     * @Template("SplicedCmsBundle:Admin/ContentBlock:edit.html.twig")
     */
    public function editAction($id)
    {
        $contentBlock = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:ContentBlock')
            ->findOneById($id);
        if (!$contentBlock) {
            return $this->createNotFoundException('Content Block Not Found');
        }
        $form = $this->createContentBlockForm($contentBlock);
        return array(
            'contentBlock' => $contentBlock,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/update/{id}", name="spliced_cms_admin_content_block_update")
     * @Template("SplicedCmsBundle:Admin/ContentBlock:edit.html.twig")
     */
    public function updateAction($id)
    {
        $contentBlock = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:ContentBlock')
            ->findOneById($id);
        if (!$contentBlock) {
            return $this->createNotFoundException('Content Block Not Found');
        }
        $originalContent = $contentBlock
            ->getTemplate()
            ->getVersion()
            ->getContent();
        $form = $this->createContentBlockForm($contentBlock);
        if ($form->submit($this->get('request')) && $form->isValid()) {
            $contentBlock = $form->getData();
            $userLabel = trim($form['template']['label']->getData());
            if (md5($originalContent) != md5(trim($contentBlock->getTemplate()->getVersion()->getContent()))) {
                // save new version
                $templateVersion = new TemplateVersion();
                $templateVersion->setTemplate($contentBlock->getTemplate())
                    ->setLabel($userLabel ? $userLabel : date('m/d/Y h:i:a'))
                    ->setContent(trim($contentBlock->getTemplate()->getVersion()->getContent()));
            } else {
                if ($userLabel) {
                    $contentBlock->getTemplate()->getVersion()->setLabel($userLabel);
                }
            }
            try {
                $this->get('spliced_cms.content_block_manager')->update($contentBlock);
                $this->get('session')->getFlashBag()->add('success', 'Content Block Successfully Updated');
                return $this->redirect($this->generateUrl('spliced_cms_admin_content_block_edit', array(
                    'id' => $contentBlock->getId()
                )));
            } catch (\Exception $e) {
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                $this->get('session')->getFlashBag()->add('error', 'Error Updating Content Block');
                return array(
                    'contentBlock' => $contentBlock,
                    'form' => $form->createView()
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'You submission contains invalid data.');
        }
        return array(
            'contentBlock' => $contentBlock,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/filter", name="spliced_cms_admin_content_block_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }

    /**
     * @Route("/filter/reset", name="spliced_cms_admin_content_block_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }

    /**
     * @Route("/batch", name="spliced_cms_admin_content_block_batch")
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
    public function batchDelete(Request $request, $contentBlocks)
    {
        $deleted = 0;
        foreach ($contentBlocks as $contentBlock) {
            try {
                $this->get('spliced_cms.content_page_manager')->delete($contentBlock);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Content Blocks', $deleted, count($contentBlocks)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_block_list'));
    }

    /**
     * batchActivate
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchActivate(Request $request, $contentBlocks)
    {
        $updated = 0;
        foreach ($contentBlocks as $contentBlock) {
            try {
                $contentBlock->setIsActive(true);
                $this->get('spliced_cms.content_block_manager')->update($contentBlock);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Activated %s/%s Content Blocks', $updated, count($contentBlocks)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_block_list'));
    }

    /**
     * batchDeactivate
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchDeactivate(Request $request, $contentBlocks)
    {
        $updated = 0;
        foreach ($contentBlocks as $contentBlock) {
            try {
                $contentBlock->setIsActive(false);
                $this->get('spliced_cms.content_block_manager')->update($contentBlock);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deactivated %s/%s Content Blocks', $updated, count($contentBlocks)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_block_list'));
    }

    /**
     * batchPublish
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchPublish(Request $request, $contentBlocks)
    {
        $updated = 0;
        foreach ($contentBlocks as $contentBlock) {
            try {
                $contentBlock->getTemplate()->setActiveVersion($contentBlock->getTemplate()->getVersion());
                $this->get('spliced_cms.content_block_manager')->update($contentBlock);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Published Changes to %s/%s Content Blocks', $updated, count($contentBlocks)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_block_list'));
    }

    /**
     * @param ContentPage $contentPage
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createContentBlockForm(ContentBlock $contentBlock = null, array $options = array())
    {
        if (is_null($contentBlock)) {
            $contentBlock = new ContentBlock();
        }
        return $this->createForm(new ContentBlockFormType($contentBlock), $contentBlock, $options);
    }

    /**
     * {@inheritDoc}
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_content_block_list');
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
        return $this->createForm(new ContentBlockFilterFormType(), $this->getFilters());
    }

    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_content_block_filter';
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getCrudClass()
    {
        return 'SplicedCmsBundle:ContentBlock';
    }
}

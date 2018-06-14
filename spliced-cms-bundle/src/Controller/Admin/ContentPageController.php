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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\CmsBundle\Form\Type\ContentPageFilterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spliced\Bundle\CmsBundle\Entity\ContentPage;
use Spliced\Bundle\CmsBundle\Entity\Template as CmsTemplate;
use Spliced\Bundle\CmsBundle\Entity\TemplateVersion;
use Spliced\Bundle\CmsBundle\Form\Type\ContentPageFormType;
use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\ContentPageEvent;
use Spliced\Bundle\CmsBundle\Templating\TemplateBlockHelper;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/content_page")
 */
class ContentPageController extends BaseCrudController
{

	/**
	 * @Route("/", name="spliced_cms_admin_content_page_list")
	 * @Template()
	 */
    public function listAction(Request $request)
    {
    	$contentPagesQuery = $this->getDoctrine()
    	  ->getRepository('SplicedCmsBundle:ContentPage')
    	  ->getFilteredQuery($this->getFilters());
        
        $currentSite = $this->get('spliced_cms.site_manager')->getCurrentAdminSite();
        
        if ($currentSite) {
            $contentPagesQuery->where('c.site = :site')
                ->setParameter('site', $currentSite->getId());
        }
    	
    	$contentPages = $this->get('knp_paginator')->paginate(
    		$contentPagesQuery,
            $request->query->get('page', 1),
    		25
    	);
    	
    	return array(
    		'contentPages' => $contentPages,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
    	);
    }    
    
    /**
     * @Route("/new", name="spliced_cms_admin_content_page_new")
     * @Template()
     */
    public function newAction()
    {
    	return array(
    		'form' => $this->createContentPageForm()->createView()
    	);
    }
    
    /**
     * @Route("/save", name="spliced_cms_admin_content_page_save")
     * @Template("SplicedCmsBundle:Admin/ContentPage:new.html.twig")
     */
    public function saveAction(Request $request)
    {
        $siteManager = $this->get('spliced_cms.site_manager');
        $form = $this->createContentPageForm();
        
        if ($form->submit($request) && $form->isValid()) {
            $contentPage = $form->getData();

            if (!$contentPage->getSite()) {
                $contentPage->setSite($siteManager->getCurrentAdminSite());
            }
            
            $template = $contentPage->getTemplate();
            
            $templateVersion = $template->getVersion();

            $templateVersion->setTemplate($template);
            
            if (strlen(trim($templateVersion->getContent()))) {
                try {
                    
                    $this->get('spliced_cms.content_page_manager')->save($contentPage);
                    
                    $this->get('session')->getFlashBag()->add('success', 'Content Page Successfully Created');
                    
                    return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_edit', array(
                        'id' => $contentPage->getId()
                    )));

                } catch (\Exception $e) {
                    if ($this->get('kernel')->getEnvironment() == 'dev') {
                        throw $e;
                    }

                    $this->get('session')->getFlashBag()->add('error', 'Error Saving Content Page');
                    
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
     * @Route("/edit/{id}", name="spliced_cms_admin_content_page_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
    	$contentPage = $this->getDoctrine()
    	->getRepository('SplicedCmsBundle:ContentPage')
    	->findOneById($id);
    	
        if (!$contentPage) {
    		return $this->createNotFoundException('Content Page Not Found');
    	}
    	
    	$form = $this->createContentPageForm($contentPage);
        
        $contentPageBlocks = TemplateBlockHelper::getAllBlocks($contentPage->getTemplate()->getVersion()->getContent());
        
        $layoutBlocks = array();
        
        if ($contentPage->getLayout()) {
            $layoutBlocks = TemplateBlockHelper::getAllBlocks($contentPage->getLayout()->getTemplate()->getVersion()->getContent());
        }
    	
        return array(
    		'contentPage' => $contentPage,
    		'form' => $form->createView(),
            'deleteForm' => $this->createDeleteForm($contentPage)->createView(),
            'definedBlocks' => array(
                'contentPage' => $contentPageBlocks,
                'layout' => $layoutBlocks
            ),
    	);
    }
    
    /**
     * @Route("/update/{id}", name="spliced_cms_admin_content_page_update")
     * @Template("SplicedCmsBundle:Admin/ContentPage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
    	$contentPage = $this->getDoctrine()
    	->getRepository('SplicedCmsBundle:ContentPage')
    	->findOneById($id);
    
    	if (!$contentPage) {
    		return $this->createNotFoundException('Content Page Not Found');
    	}
        
        if (!$contentPage->getTemplate() || !$contentPage->getTemplate()->getVersion()) {
            throw new \RuntimeException(sprintf('Expected a Template and a Template Version'));
        }

        $originalContent = $contentPage
          ->getTemplate()
          ->getVersion()
          ->getContent();

    	$form = $this->createContentPageForm($contentPage);

        if ($form->submit($request) && $form->isValid()) {
            
            $contentPage = $form->getData();
            
            $userLabel = trim($form['template']['label']->getData());
            
            if (md5($originalContent) != md5(trim($contentPage->getTemplate()->getVersion()->getContent()))) {
                // save new version
                $templateVersion = new TemplateVersion();
                $templateVersion->setTemplate($contentPage->getTemplate())
                  ->setLabel($userLabel ? $userLabel : date('m/d/Y h:i:a'))
                 ->setContent(trim($contentPage->getTemplate()->getVersion()->getContent()));
                $contentPage->getTemplate()->setVersion($templateVersion);
            } else {
                if ($userLabel) {
                    $contentPage->getTemplate()->getVersion()->setLabel($userLabel);
                }
            }

            try {
                
                $this->get('spliced_cms.content_page_manager')->update($contentPage);
                
                $this->get('session')->getFlashBag()->add('success', 'Content Page Successfully Updated');
                
                return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_edit', array(
                    'id' => $contentPage->getId()
                )));

            } catch (\Exception $e) {
                
                if ($this->get('kernel')->getEnvironment() == 'dev') {
                    throw $e;
                }
                
                $this->get('session')->getFlashBag()->add('error', 'Error Updating Content Page');
                
                return array(
                    'contentPage' => $contentPage,
                    'form' => $form->createView()
                );
            }

        } else {
            $this->get('session')->getFlashBag()->add('error', 'You submission contains invalid data');
        }

    	return array(
    		'contentPage' => $contentPage,
    		'form' => $form->createView()
    	);
    }
    
    /**
     * @Route("/edit/{id}/publish-revisions", name="spliced_cms_admin_content_page_publish_revisions")
     * @Template()
     */
    public function publishRevisionsAction($id)
    {
        $contentPage = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:ContentPage')
            ->findOneById($id);
        
        if (!$contentPage) {
            return $this->createNotFoundException('Content Page Not Found');
        }
        
        if (!$contentPage->getTemplate() || !$contentPage->getTemplate()->getVersion()) {
            throw new \RuntimeException(sprintf('Expected a Template and a Template Version'));
        }

        $contentPage->getTemplate()->setActiveVersion($contentPage->getTemplate()->getVersion());
        
        $this->get('spliced_cms.content_page_manager')->update($contentPage);
        
        $this->get('session')->getFlashBag()->add('success', 'Latest Revisions Published');
        
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_edit', array(
            'id' => $contentPage->getId()
        )));
    }

    /**
     * @Route("/filter", name="spliced_cms_admin_content_page_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }

    /**
     * @Route("/filter/reset", name="spliced_cms_admin_content_page_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }

    /**
     * @Route("/batch", name="spliced_cms_admin_content_page_batch")
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
    public function batchDelete(Request $request, $contentPages)
    {
        $deleted = 0;
        foreach ($contentPages as $contentPage) {
            try {
                $this->get('spliced_cms.content_page_manager')->delete($contentPage);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Content Pages', $deleted, count($contentPages)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_list'));
    }

    /**
     * batchActivate
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchActivate(Request $request, $contentPages)
    {
        $updated = 0;
        foreach ($contentPages as $contentPage) {
            try {
                $contentPage->setIsActive(true);
                $this->get('spliced_cms.content_page_manager')->update($contentPage);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Activated %s/%s Content Pages', $updated, count($contentPages)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_list'));
    }

    /**
     * batchDeactivate
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchDeactivate(Request $request, $contentPages)
    {
        $updated = 0;
        
        foreach ($contentPages as $contentPage) {
            try {
                $contentPage->setIsActive(false);
                $this->get('spliced_cms.content_page_manager')->update($contentPage);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        
        $this->get('session')->getFlashBag()->add('success', sprintf('Deactivated %s/%s Content Pages', $updated, count($contentPages)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_list'));
    }

    /**
     * batchPublish
     *
     * @param Request $request
     * @param $contentPages
     */
    public function batchPublish(Request $request, $contentPages)
    {
        $updated = 0;
        
        foreach ($contentPages as $contentPage) {
            try {
                $contentPage->getTemplate()->setActiveVersion($contentPage->getTemplate()->getVersion());
                $this->get('spliced_cms.content_page_manager')->update($contentPage);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Published Changes to %s/%s Content Pages', $updated, count($contentPages)));
        
        return $this->redirect($this->generateUrl('spliced_cms_admin_content_page_list'));
    }

    /**
     * @param ContentPage $contentPage
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    private function createContentPageForm(ContentPage $contentPage = null, array $options = array())
    {
    	if (is_null($contentPage)) {
    		$contentPage = new ContentPage();
    	}
    	
    	return $this->createForm(new ContentPageFormType($this->get('spliced_cms.site_manager'), $contentPage), $contentPage, $options);
    }

    /**
     * {@inheritDoc}
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_content_page_list');
    }

    /**
     * {@inheritDoc}
     */
    protected function getCrudClass()
    {
        return 'SplicedCmsBundle:ContentPage';
    }

    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_content_page_filter';
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
        return $this->createForm(new ContentPageFilterFormType(), $this->getFilters());
    }
}

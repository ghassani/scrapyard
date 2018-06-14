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

use Spliced\Bundle\CmsBundle\Form\Type\SiteFilterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spliced\Bundle\CmsBundle\Entity\Site;
use Spliced\Bundle\CmsBundle\Form\Type\SiteFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/site")
 */
class SiteController extends BaseCrudController
{

    /**
     * @Route("/", name="spliced_cms_admin_site_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $sitesQuery = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:Site')
            ->getFilteredQuery($this->getFilters())
            ->leftJoin('s.aliases', 'a')
            ->where('s.aliasOf IS NULL');
        
        $sites = $this->get('knp_paginator')->paginate(
            $sitesQuery,
            $request->query->get('page', 1),
            25
        );
        
        return array(
            'sites' => $sites,
            'batchActionForm' => $this->createBatchActionForm()->createView(),
            'filterForm' => $this->getFilterForm()->createView(),
        );
    }

    /**
     * @Route("/new", name="spliced_cms_admin_site_new")
     * @Template()
     */
    public function newAction()
    {
        return array(
            'form' => $this->createSiteForm()->createView()
        );
    }

    /**
     * @Route("/save", name="spliced_cms_admin_site_save")
     * @Template("SplicedCmsBundle:Admin/Site:new.html.twig")
     */
    public function saveAction(Request $request)
    {
        $form = $this->createSiteForm();
        if ($form->submit($request) && $form->isValid()) {
            
            $site = $form->getData();
            
            $existingSite = $this->getDoctrine()
                ->getRepository('SplicedCmsBundle:Site')
                ->findOneByDomain($site->getDomain());
            
            if (!$existingSite) {

                try {

                    $this->get('spliced_cms.site_manager')->save($site);

                    $this->get('session')->getFlashBag()->add('success', 'Site Successfully Created');
                    
                    return $this->redirect($this->generateUrl('spliced_cms_admin_site_edit', array(
                        'id' => $site->getId()
                    )));

                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', sprintf('Error Saving Site: %s', $e->getMessage()));
                }
            
            } else {
                $this->get('session')->getFlashBag()->add('error', sprintf('Site With Domain %s Already Exists', $site->getDomain()));
            }
        
        } else {
            $this->get('session')->getFlashBag()->add('error', 'There was a problem validating your input');
        }
        
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/edit/{id}", name="spliced_cms_admin_site_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $site = $this->getDoctrine()->getRepository('SplicedCmsBundle:Site')
           ->findOneById($id);

        if (!$site) {
            throw $this->createNotFoundException('Site Not Found');
        }

        $form = $this->createSiteForm($site);
        
        $users = $this->getDoctrine()
            ->getManager()
            ->getRepository('SplicedCmsBundle:User')
            ->createQueryBuilder('u')
            ->select('u')
            ->getQuery()
            ->getResult();

        return array(
            'form' => $form->createView(),
            //'users' => $users
        );
    }

    /**
     * @Route("/update/{id}", name="spliced_cms_admin_site_update")
     * @Template("SplicedCmsBundle:Admin/Site:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $site = $this->getDoctrine()->getRepository('SplicedCmsBundle:Site')
            ->findOneById($id);
        
        if (!$site) {
            throw $this->createNotFoundException('Site Not Found');
        }
        
        $form = $this->createSiteForm($site);
        
        if ($form->submit($request) && $form->isValid()) {

            $this->get('spliced_cms.site_manager')->update($site);
            
            $this->get('session')->getFlashBag()->add('success', 'Site Successfully Updated');
        
            return $this->redirect($this->generateUrl('spliced_cms_admin_site_edit', array(
                'id' => $site->getId()
            )));
        }
        
        $this->get('session')->getFlashBag()->add('error', 'There was an error validating your input.');
        
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/filter", name="spliced_cms_admin_site_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        return parent::filterAction($request);
    }

    /**
     * @Route("/filter/reset", name="spliced_cms_admin_site_filter_reset")
     * @Method("POST")
     */
    public function resetFilterAction(Request $request)
    {
        return parent::resetFilterAction($request);
    }

    /**
     * @Route("/batch", name="spliced_cms_admin_site_batch")
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
     * @param $sites
     */
    public function batchDelete(Request $request, $sites)
    {
        $deleted = 0;
        foreach ($sites as $site) {
            try {
                $this->get('spliced_cms.site_manager')->delete($site);
                $deleted++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deleted %s/%s Sites', $deleted, count($sites)));
        
        return $this->redirect($this->generateUrl('spliced_cms_admin_site_list'));
    }

    /**
     * batchActivate
     *
     * @param Request $request
     * @param $sites
     */
    public function batchActivate(Request $request, $sites)
    {
        $updated = 0;
        foreach ($sites as $site) {
            try {
                $site->setIsActive(true);
                $this->get('spliced_cms.site_manager')->update($site);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Activated %s/%s Sites', $updated, count($sites)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_site_list'));
    }

    /**
     * batchDeactivate
     *
     * @param Request $request
     * @param $sites
     */
    public function batchDeactivate(Request $request, $sites)
    {
        $updated = 0;
        foreach ($sites as $site) {
            try {
                $site->setIsActive(false);
                $this->get('spliced_cms.site_manager')->update($site);
                $updated++;
            } catch(\Exception $e) {
                if (in_array($this->get('kernel')->getEnvironment(), array('dev','test'))){
                    throw $e;
                }
            }
        }
        $this->get('session')->getFlashBag()->add('success', sprintf('Deactivated %s/%s Sites', $updated, count($sites)));
        return $this->redirect($this->generateUrl('spliced_cms_admin_site_list'));
    }

    /**
     * @param Site $site
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function createSiteForm(Site $site = null, array $options = array())
    {
        if (is_null($site)) {
            $site = new Site();
        }
        return $this->createForm(new SiteFormType($site), $site, $options);
    }

    /**
     * {@inheritDoc}
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_site_list');
    }

    /**
     * {@inheritDoc}
     */
    protected function getCrudClass()
    {
        return 'SplicedCmsBundle:Site';
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
        return $this->createForm(new SiteFilterFormType(), $this->getFilters());
    }

    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_site_filter';
    }

}

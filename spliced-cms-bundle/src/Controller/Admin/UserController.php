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
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/%spliced_cms.admin_route_prefix%/user")
 */
class UserController extends BaseCrudController
{
    /**
     * @Route("/", name="spliced_cms_admin_user_list")
     * @Template()
     */
    public function listAction(Request $request)
    {

        $usersQuery = $this->getDoctrine()
            ->getRepository('SplicedCmsBundle:User')
            ->createQueryBuilder('u');

        $users = $this->get('knp_paginator')->paginate(
            $usersQuery,
            $request->query->get('page', 1),
            25
        );

        return array(
            'users' => $users
        );

    }

    /**
     * @Route("/new", name="spliced_cms_admin_user_new")
     * @Template()
     */
    public function newAction()
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function getBatchRedirect()
    {
        return $this->generateUrl('spliced_cms_admin_user_list');
    }

    /**
     * {@inheritDoc}
     */
    protected function getCrudClass()
    {
        return 'SplicedCmsBundle:User';
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilterForm()
    {
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getSessionKey()
    {
        return 'spliced_cms_user_filter';
    }
}
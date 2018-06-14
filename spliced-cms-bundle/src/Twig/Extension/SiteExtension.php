<?php

namespace Spliced\Bundle\CmsBundle\Twig\Extension;

use Spliced\Bundle\CmsBundle\Form\Type\SiteSelectionFormType;

class SiteExtension extends \Twig_Extension
{

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_admin_site_selection_form', array($this, 'getSiteSelectionForm')),
        );
    }

    /**
     *
     */
    public function getSiteSelectionForm()
    {
        return $this->container->get('form.factory')->create(new SiteSelectionFormType(), array(
            'site' => $this->container->get('spliced_cms.site_manager')->getCurrentAdminSite()
        ), array())->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_cms_site';
    }

}
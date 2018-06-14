<?php

namespace Spliced\Bundle\CmsBundle\Twig\Extension;

use Spliced\Bundle\CmsBundle\Event\Event;
use Spliced\Bundle\CmsBundle\Event\TemplateRenderEvent;

class MenuExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('render_menu', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($menuKey)
    {
        $site = $this->container->get('spliced_cms.site_manager')->getCurrentSite();
        $eventDispatcher = $this->container->get('event_dispatcher');
        $request = $this->container->get('request');
        $em = $this->container->get('doctrine.orm.entity_manager');

        $menu = $em
            ->getRepository('SplicedCmsBundle:Menu')
            ->findOneBy(array(
                'menuKey' => $menuKey,
                'site' => $site
        ));

        if (!$menu) {
            return false;
        }

        if ($menu->getMenuTemplate() && $menuTemplate =  $menu->getMenuTemplate()->getTemplate()) {

            $event = $eventDispatcher->dispatch(
                Event::TEMPLATE_RENDER,
                new TemplateRenderEvent($request, $menuTemplate, null, array('menu' => $menu))
            );


            return $event->getResponse()->getContent();


            return $this->container->get('spliced_cms.template_manager')->render($menuTemplate, array(
                'menu' => $menu
            ));
        }

        return $this->container->get('templating')->render('SplicedCmsBundle:Default:menu.html.twig', array(
            'menu' => $menu
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spliced_cms_menu';
    }

}
<?php

namespace Spliced\Bundle\ProjectManagerBundle\EventListener;

use Spliced\Bundle\AdminThemeBundle\Event\MenuBuilderEvent;

class AdminThemeMenuEventListener
{

    public function buildAdminMainMenu(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();



        $event->setMenu($menu);
    }

    public function buildAdminSlideOutMenu(MenuBuilderEvent $event)
    {
        $event->setMenu(array_replace_recursive($event->getMenu(), array(
            20 => array (
                'uri' => 'javascript:;',
                'label' => '<i class="fa fa-gear fa-fw"></i> Project Management',
                'children' => array(
                    array(
                        'route' => 'spliced_pms_project_list',
                        'label' => '<i class="fa fa-tasks"></i> Projects',
                    ),
                    array(
                        'route' => 'spliced_pms_client_list',
                        'label' => '<i class="fa fa-thumbs-o-up"></i> Clients',
                    ),
                    array(
                        'route' => 'spliced_pms_tag_list',
                        'label' => '<i class="fa fa-tags"></i> Tags',
                    ),
                    array(
                        'route' => 'spliced_pms_staff_list',
                        'label' => '<i class="fa fa-user-secret"></i> Staff',
                    ),
                    array(
                        'route' => 'spliced_pms_user_list',
                        'label' => '<i class="fa fa-users"></i> Users',
                    )
                )
            )
        )));

    }
}
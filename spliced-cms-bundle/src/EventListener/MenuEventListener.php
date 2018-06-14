<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\EventListener;

use Spliced\Bundle\AdminThemeBundle\Event\MenuBuilderEvent;
use Spliced\Bundle\CmsBundle\Manager\SiteManager;

class MenuEventListener
{
    public function __construct(SiteManager $siteManager)
    {
        $this->siteManager = $siteManager;
    }

    public function buildAdminMainMenu(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();
        $event->setMenu($menu);
    }

    public function buildAdminSlideOutMenu(MenuBuilderEvent $event)
    {
        $event->setMenu(array_replace_recursive($event->getMenu(), array(
            5 => array(
                'route' => 'spliced_cms_admin_dashboard',
                'label' => '<i class="fa fa-desktop"></i> Dashboard',
            ),
            10 => array(
                'route' => 'spliced_cms_admin_site_list',
                'label' => '<i class="fa fa-desktop"></i> Site Manager',
            ),
            15 => array(
                'uri' => 'javascript:void(0);',
                'label' => '<i class="fa fa-file-text-o fa-fw"></i> Content',
                'children' => array(
                    5 => array(
                        'route' => 'spliced_cms_admin_content_page_list',
                        'label' => '<i class="fa fa-newspaper-o"></i> Content Pages'
                    ),
                    10 => array(
                        'route' => 'spliced_cms_admin_content_block_list',
                        'label' => '<i class="fa fa-table"></i> Content Blocks'
                    ),
                    15 => array(
                        'route' => 'spliced_cms_admin_layout_list',
                        'label' => '<i class="fa fa-paint-brush"></i> Layouts'
                    ),
                    20 => array(
                        'route' => 'spliced_cms_admin_menu_list',
                        'label' => '<i class="fa fa-list-ul"></i> Menus'
                    ),
                    25 => array(
                        'route' => 'spliced_cms_admin_menu_template_list',
                        'label' => '<i class="fa fa-list-alt"></i> Menu Templates'
                    ),
                    30 => array(
                        'route' => 'spliced_cms_admin_file_manager',
                        'label' => '<i class="fa fa-files-o"></i> File Management'
                    ),
                    35 => array(
                        'route' => 'spliced_cms_admin_gallery',
                        'label' => '<i class="fa fa-image"></i> Media Gallery'
                    )
                )
            ),
            100 => array(
                'route' => 'spliced_cms_admin_configuration_item_list',
                'label' => '<i class="fa fa-cogs"></i> System Configuration'
            )
        )));
    }

    public function buildAdminUserMenu(MenuBuilderEvent $event)
    {
        $event->setMenu(array_replace_recursive($event->getMenu(), array(
            20 => array(
                'uri' => 'javascript:void(0);',
                'label' => '<i class="fa fa-user fa-fw"></i>',
                'children' => array(
                    array(
                        'uri' => 'javascript:void(0);',
                        'label' => '<i class="fa fa-user fa-fw"></i> Profile',
                    ),
                    array(
                        'uri' => 'javascript:void(0);',
                        'label' => '<i class="fa fa-sign-out fa-fw"></i> Logout',
                    )
                )
            ),
            25 => array(
                'uri' => 'javascript:void(0);',
                'label' => '<i class="fa fa-gear fa-plus"></i>',
                'children' => array(
                    array(
                        'route' => 'spliced_cms_admin_content_page_new',
                        'label' => 'New Content Page',
                    ),
                    array(
                        'route' => 'spliced_cms_admin_content_block_new',
                        'label' => 'New Content Block',
                    ),
                    array(
                        'route' => 'spliced_cms_admin_layout_new',
                        'label' => 'New Layout',
                    ),
                    array(
                        'route' => 'spliced_cms_admin_menu_new',
                        'label' => 'New Menu',
                    ),
                    array(
                        'route' => 'spliced_cms_admin_menu_template_new',
                        'label' => 'New Menu Template',
                    )
                )
            )
        )));
    }
    
}
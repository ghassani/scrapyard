<?php
/*
* This file is part of the SplicedAdminThemeBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\AdminThemeBundle\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    const BUILD_MAIN_MENU   = 'spliced.admin_theme.build_menu.main';
    const BUILD_MAIN_USER   = 'spliced.admin_theme.build_menu.user';
    const BUILD_MAIN_FOOTER = 'spliced.admin_theme.build_menu.footer';
    const BUILD_SLIDE_OUT_MENU = 'spliced.admin_theme.build_menu.slide_out';
} 
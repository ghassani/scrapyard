<?php
/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Bundle\CmsBundle\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    const CONTENT_PAGE_SAVE          = 'spliced_cms.content_page.save';
    const CONTENT_PAGE_UPDATE        = 'spliced_cms.content_page.update';
    const CONTENT_PAGE_DELETE        = 'spliced_cms.content_page.delete';
    const CONTENT_PAGE_VIEW          = 'spliced_cms.content_page.view';
    const CONTENT_PAGE_PRE_RENDER    = 'spliced_cms.content_page.pre_render';
    const CONTENT_PAGE_POST_RENDER   = 'spliced_cms.content_page.post_render';
    const CONTENT_BLOCK_SAVE         = 'spliced_cms.content_block.save';
    const CONTENT_BLOCK_UPDATE       = 'spliced_cms.content_block.update';
    const CONTENT_BLOCK_DELETE       = 'spliced_cms.content_block.delete';
    const CONTENT_BLOCK_RENDER       = 'spliced_cms.content_block.render';
    const TEMPLATE_SAVE              = 'spliced_cms.template.save';
    const TEMPLATE_UPDATE            = 'spliced_cms.template.update';
    const TEMPLATE_DELETE            = 'spliced_cms.template.delete';
    const TEMPLATE_RENDER            = 'spliced_cms.template.render';
    const TEMPLATE_PRE_RENDER        = 'spliced_cms.template.pre_render';
    const TEMPLATE_POST_RENDER       = 'spliced_cms.template.post_render';
    const LAYOUT_SAVE                = 'spliced_cms.layout.save';
    const LAYOUT_UPDATE              = 'spliced_cms.layout.update';
    const LAYOUT_DELETE              = 'spliced_cms.layout.delete';
    const MENU_SAVE                  = 'spliced_cms.menu.save';
    const MENU_UPDATE                = 'spliced_cms.menu.update';
    const MENU_DELETE                = 'spliced_cms.menu.delete';
    const MENU_TEMPLATE_SAVE         = 'spliced_cms.menu_template.save';
    const MENU_TEMPLATE_UPDATE       = 'spliced_cms.menu_template.update';
    const MENU_TEMPLATE_DELETE       = 'spliced_cms.menu_template.delete';
    const SITE_SAVE                  = 'spliced_cms.site.save';
    const SITE_UPDATE                = 'spliced_cms.site.update';
    const SITE_DELETE                = 'spliced_cms.site.delete';
}
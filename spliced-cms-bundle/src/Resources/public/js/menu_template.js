/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedMenuTemplate ( element, menuTemplate, options ) {
    this.element     = $(element);
    this.menuTemplate = menuTemplate;
    this.settings    = $.extend( {}, {
        debug : false,
        editor_id : 'menu_template_template_version_content_ace'
    }, options );
    this.init();
}

$.extend(SplicedMenuTemplate.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        this.editor = new SplicedCmsEditor(document.getElementById(this.settings.editor_id), { debug : this.settings.debug });

        this.tabs = this.element.find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.cookie('last-content-block-tab-' + self.menuTemplate.id, $(e.target).attr('href'));
        });

        var lastTab = $.cookie('last-page-tab-' + this.menuTemplate.id);

        if (typeof lastTab != 'undefined') {
            this.element.find('a[href="' + lastTab + '"]').tab('show');
        }

        this.log('SplicedMenuTemplate Initialized', this.element);
    }

});



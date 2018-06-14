/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedLayout ( element, layout, options ) {
    this.element     = $(element);
    this.layout = layout;
    this.settings    = $.extend( {}, {
        debug : false,
        editor_id : 'layout_template_version_content_ace',
        content_page_editor_id : 'layout_contentPageTemplate_version_content_ace',
        selector : {
            extension_list : '#layout-extensions'
        }
    }, options );
    this.init();
}

$.extend(SplicedLayout.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        this.registerExtensionEvents();

        this.editor = new SplicedCmsEditor(document.getElementById(this.settings.editor_id), { debug : this.settings.debug });
        this.contentPageEditor = new SplicedCmsEditor(document.getElementById(this.settings.content_page_editor_id), { debug : this.settings.debug });

        this.tabs = this.element.find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.cookie('last-layout-tab-' + self.layout.id, $(e.target).attr('href'));
        });

        var lastTab = $.cookie('last-layout-tab-' + this.layout.id);

        if (typeof lastTab != 'undefined') {
            this.element.find('a[href="' + lastTab + '"]').tab('show');
        }


        this.log('SplicedLayout Initialized', this.element);
    },
    /**
     * registerExtensionEvents
     */
    registerExtensionEvents : function() {

        var self = this;
        this.templateExtensionList = new SplicedTemplateExtensionList($(this.settings.selector.extension_list).get(0), {
            debug : true,
            selector : {
                add_extension    : 'a.layout-add-extension',
                edit_extension   : 'a.edit-extension',
                delete_extension : 'a.delete-extension'
            },
            onAddExtension : function($response, self){ return; },
            onAddExtensionError         : function($response){ return; },
            onAddExtensionStep2         : function($response, $firstResponse){
                $firstResponse.find('.js-switch').each(function(){
                    new Switchery(this);
                });
            },
            onAddExtensionStep2Error    : function($response){ return; },
            onAddExtensionSave          : function($response, self){
                $("table.layout-extensions-table tbody").append($response);
                $response.modal('toggle');
            },
            onAddExtensionSaveError     : function($response){ return; },
            onEditExtension             : function($response){
                $response.find('.js-switch').each(function(){
                    new Switchery(this);
                });
            },
            onEditExtensionError        : function($response){ return; },
            onEditExtensionUpdate       : function($response){ return; },
            onEditExtensionUpdateError  : function($response){ return; },
            onDeleteExtension           : function(response, initiatingElement){
                $(initiatingElement).closest('tr').fadeOut('slow', function(){
                    $(this).remove();
                });
            },
            onDeleteExtensionError      : function(response){ return; }
        });

    }
});



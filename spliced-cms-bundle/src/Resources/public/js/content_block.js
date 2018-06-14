/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedContentBlock ( element, contentBlock, options ) {
    this.element     = $(element);
    this.contentBlock = contentBlock;
    this.settings    = $.extend( {}, {
        debug : false,
        editor_id : 'content_block_template_version_content_ace',
        selector : {
            extension_list : '#content-block-extensions'
        }
    }, options );
    this.init();
}

$.extend(SplicedContentBlock.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        this.editor = new SplicedCmsEditor(document.getElementById(this.settings.editor_id), { debug : this.settings.debug });

        this.registerExtensionEvents();

        this.tabs = this.element.find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.cookie('last-content-block-tab-' + self.contentBlock.id, $(e.target).attr('href'));
        });

        var lastTab = $.cookie('last-page-tab-' + this.contentBlock.id);

        if (typeof lastTab != 'undefined') {
            this.element.find('a[href="' + lastTab + '"]').tab('show');
        }


        this.log('SplicedContentBlock Initialized', this.element);
    },
    /**
     * registerExtensionEvents
     */
    registerExtensionEvents : function() {

        var self = this;

        this.templateExtensionList = $(this.settings.selector.extension_list).splicedTemplateExtensionList({
            debug : true,
            selector : {
                add_extension    : 'a.content-block-add-extension',
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
                $("table.content-block-extensions-table tbody").append($response);
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



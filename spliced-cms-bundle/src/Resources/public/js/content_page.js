/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedContentPage ( element, contentPage, options ) {
    this.element     = $(element);

    this.contentPage = contentPage;

    this.settings    = $.extend( {}, {
        debug : false,
        editor_id : 'content_page_template_version_content_ace',
        selector : {
            extension_list : '#page-content-extensions'
        }
    }, options );

    this.init();
}

$.extend(SplicedContentPage.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        this.registerExtensionEvents();

        this.editor = new SplicedCmsEditor(document.getElementById(this.settings.editor_id), { debug : this.settings.debug });

        this.tabs = this.element.find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.cookie('last-page-tab-' + self.contentPage.id, $(e.target).attr('href'));
        });

        var lastTab = $.cookie('last-page-tab-' + this.contentPage.id);

        if (typeof lastTab != 'undefined') {
            this.element.find('a[href="' + lastTab + '"]').tab('show');
        }

        this.element.find('#content_page_layout').on('change', function(e){
            if (!$(this).val()) {
                return;
            }
            $.ajax({
                url : '/dev.php/admin/json/layout/load/' + $(this).val(),
                method : 'GET',
                dataType : 'json',
                beforeSend: function(){
                    self.setLoading();
                },
                error : function() {

                },
                complete: function() {
                    self.clearLoading();
                },
                success : function(response) {
                    if (response.success) {
                        if (typeof response.data.contentPageTemplate != 'undefined' && !$("#content_page_template_version_content").val()) {
                            self.editor.getEditor().insert(response.data.contentPageTemplate.version.content);
                        }
                    }
                }
            });

        });

        this.log('SplicedContentPage Initialized', this.element);
    },
    /**
     * registerExtensionEvents
     */
    registerExtensionEvents : function() {

        var self = this;

        this.templateExtensionList = new SplicedTemplateExtensionList($(this.settings.selector.extension_list).get(0), {
            debug : true,
            selector : {
                add_extension    : 'a.content-page-add-extension',
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
                $("table.content-page-extensions-table tbody").append($response);
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



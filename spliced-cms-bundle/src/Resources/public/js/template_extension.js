/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedTemplateExtensionList ( element, options ) {
    this.element     = element;
    this.extensionList = $(element);

    this.settings    = $.extend( {}, {
        debug : false,
        selector : {
            add_extension    : 'a.add-extension',
            edit_extension   : 'a.edit-extension',
            delete_extension : 'a.delete-extension'
        },
        onAddExtension              : function($response){ return; },
        onAddExtensionError         : function($response){ return; },
        onAddExtensionStep2         : function($response, $firstResponse){ return; },
        onAddExtensionStep2Error    : function(jqXHR, textStatus, errorThrown){ return; },
        onAddExtensionSave          : function($response){ return; },
        onAddExtensionSaveError     : function(jqXHR, textStatus, errorThrown){ return; },
        onEditExtension             : function($response){ return; },
        onEditExtensionError        : function(jqXHR, textStatus, errorThrown){ return; },
        onEditExtensionUpdate       : function($response){ return; },
        onEditExtensionUpdateError  : function(jqXHR, textStatus, errorThrown){ return; },
        onDeleteExtension           : function(response, initiatingElement){ return; },
        onDeleteExtensionError      : function(jqXHR, textStatus, errorThrown){ return; }
    }, options );

    this.init();
}

$.extend(SplicedTemplateExtensionList.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        this.extensionList.find(this.settings.selector.add_extension).on('click', function(e){
            self.addExtension(e, this);
        });

        this.extensionList.find(this.settings.selector.edit_extension).on('click', function(e){
            self.editExtension(e, this);
        });

        this.extensionList.find(this.settings.selector.delete_extension).on('click', function(e){
            self.deleteExtension(e, this);
        });

        this.log('SplicedTemplateExtensionList Initialized', this.element);
    },

    /**
     * addExtension
     *
     *
     * @param event
     * @param element
     * @returns {boolean}
     */
    addExtension : function(event, element) {
        event.preventDefault();

        if (!$(element).data('href')) {
            this.log('Could Not Find Target Location');
            return false;
        }

        var action = $(element).data('href');
        var self = this;

        $.ajax({
            url     : action,
            method  : 'GET',
            error   : function(jqXHR, textStatus, errorThrown) {
                self.settings.onAddExtensionError.call(self, jqXHR, textStatus, errorThrown);
            },
            success : function(addExtensionResponse) {

                if (typeof addExtensionResponse == 'object') {
                    alert(addExtensionResponse.message);
                    return;
                }

                var $addExtensionResponse = $(addExtensionResponse);

                $addExtensionResponse.modal({
                    backdrop : 'static',
                    keyboard : false
                }).on('hidden.bs.modal', function (e) {
                    $addExtensionResponse.remove();
                });

                $addExtensionResponse.find('#template_extension_extensionKey').on('change', function(e){
                    if (!$(this).val()) {
                        return;
                    }
                    $.ajax({
                        url     : action,
                        method  : 'POST',
                        data    : $addExtensionResponse.find('form').serialize(),
                        error   : function(jqXHR, textStatus, errorThrown) {
                            self.settings.onAddExtensionStep2Error.call(self, jqXHR, textStatus, errorThrown);
                        },
                        success : function(addExtensionStep2Response) {

                            var $addExtensionStep2Response = $(addExtensionStep2Response);

                            $addExtensionResponse.find('#template-extension-settings-form').empty().append($addExtensionStep2Response.find('#template-extension-settings-form'));
                            $addExtensionResponse.find('button[type="submit"],input[type="submit"]').removeAttr('disabled').removeClass('disabled');

                            $addExtensionResponse.find('form').on('submit', function(e){
                                e.preventDefault();
                                $.ajax({
                                    url     : $(this).attr('action'),
                                    method  : 'POST',
                                    data    : $addExtensionResponse.find('form').serialize(),
                                    error   : function(jqXHR, textStatus, errorThrown) {
                                        self.settings.onAddExtensionSaveError.call(self, jqXHR, textStatus, errorThrown);
                                    },
                                    success : function(addExtensionSaveResponse) {
                                        var $addExtensionSaveResponse = $(addExtensionSaveResponse);

                                        $addExtensionSaveResponse.find(self.settings.selector.delete_extension).on('click', function(e){
                                            return self.deleteExtension(e, this);
                                        });

                                        $addExtensionSaveResponse.find(self.settings.selector.edit_extension).on('click', function(e){
                                            return self.editExtension(e, this);
                                        });

                                        $addExtensionResponse.modal('toggle');

                                        self.settings.onAddExtensionSave.call(self, $addExtensionSaveResponse);
                                    }
                                });
                                return false;
                            });

                            self.settings.onAddExtensionStep2.call(self, $addExtensionStep2Response, $addExtensionResponse);
                        }
                    });
                });

                self.settings.onAddExtension.call(self, $addExtensionResponse);
            }
        });
    },

    /**
     * editExtension
     *
     *
     * @param event
     * @param element
     * @returns {boolean}
     */
    editExtension : function(event, element) {
        event.preventDefault();

        var self = this;

        if (!$(element).data('href')) {
            this.log('Could Not Find Target Location');
            return false;
        }

        var action = $(element).data('href');

        $.ajax({
            url     : action,
            method  : 'GET',
            error   : function(jqXHR, textStatus, errorThrown) {
                self.settings.onEditExtensionError.call(self, jqXHR, textStatus, errorThrown);
            },
            success : function(editExtensionResponse) {
                if (typeof editExtensionResponse == 'object') {
                    alert(editExtensionResponse.message);
                    return;
                }

                var $editExtensionResponse = $(editExtensionResponse);

                $editExtensionResponse.modal({
                    backdrop : 'static',
                    keyboard : false
                }).on('hidden.bs.modal', function (e) {
                        $editExtensionResponse.remove();
                });

                $editExtensionResponse.find('form').on('submit', function(e){
                    e.preventDefault();

                    $.ajax({
                        url     : $(this).attr('action'),
                        method  : 'POST',
                        data : $(this).serialize(),
                        error   : function(jqXHR, textStatus, errorThrown) {
                            self.settings.onEditExtensionUpdateError.call(self, jqXHR, textStatus, errorThrown);
                        },
                        success : function(updateExtensionResponse) {
                            if (typeof updateExtensionResponse == 'object') {
                                alert(updateExtensionResponse.message);
                                return;
                            }

                            var $updateExtensionResponse = $(updateExtensionResponse);

                            $updateExtensionResponse.find(self.settings.selector.delete_extension).on('click', function(e){
                                return self.deleteExtension(e, this);
                            });

                            $updateExtensionResponse.find(self.settings.selector.edit_extension).on('click', function(e){
                                return self.editExtension(e, this);
                            });

                            self.settings.onEditExtensionUpdate.call(self, $updateExtensionResponse, $editExtensionResponse);

                        }
                    });

                    return false;
                });

                self.settings.onEditExtension.call(self, $editExtensionResponse);
            }
        });
    },

    /**
     * deleteExtension
     *
     *
     * @param event
     * @param element
     * @returns {boolean}
     */
    deleteExtension : function(event, element) {
        event.preventDefault();

        var targetHref = $(element).data('href');
        var self = this;

        if (this.confirmAction('Confirm Deletion', 'Are you sure?', {})) {
            $.ajax({
                url     : targetHref,
                method  : 'GET',
                dataType : 'json',
                error   : function(jqXHR, textStatus, errorThrown, element) {
                    self.settings.onDeleteExtensionError.call(self, jqXHR, textStatus, errorThrown);
                },
                success : function(response) {
                    if (!response.success) {
                        return self.notify(response.message);
                    }

                    self.settings.onDeleteExtension.call(self, response, element);


                }
            });
        }
    }
});




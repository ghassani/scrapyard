"use strict";

function SplicedProjectInvoice ( element, options ) {

    if (typeof element == 'undefined') {
        console.log('Could Not Find Invoice Wrapper');
        return;
    }

    this.element = $(element);

    this.settings = $.extend( {
        base_uri : false,
        selector : {
            list   : '#project-invoice-table',
            add    : '.project-invoice-new',
            edit   : '.project-invoice-edit',
            remove : '.project-invoice-remove'
        }
    }, options );
    this.init();
}

$.extend(SplicedProjectInvoice.prototype, SplicedProjectManagerBaseClass.prototype, {
    init: function () {

        var self = this;

        this.element.find(this.settings.selector.add).on('click', function (e) {
            self.add(e, this);
        });

        this.element.find(this.settings.selector.edit).on('click', function (e) {
            self.edit(e, this);
        });

        this.element.find(this.settings.selector.remove).on('click', function (e) {
            self.remove(e, this);
        });

    },



    /**
     * add
     */
    add : function(event, element) {
        var self = this;

        self.setWorking(true);

        $.ajax({
            url : this.settings.base_uri + '/invoice/new',
            method : 'GET',
            complete: function(){
                self.setWorking(false);
            },
            success : function(addResponse) {
                if (typeof addResponse == 'object') {
                    if (typeof addResponse.message != 'undefined') {
                        self.alert(addResponse.message);
                    }
                    return;
                }

                var $modal = $(addResponse);

                $modal.modal({
                    backdrop : 'static',
                    keyboard : false
                }).on('hidden.bs.modal', function (e) {
                    $modal.remove();
                });

                $modal.find('.project-invoice-line-item-new').on('click', function(e){
                    e.preventDefault();
                    var index = $('#project-invoice-item-table tbody tr').length;
                    var $prototype = $($(this).data('prototype').replace(/__name__/g, index));

                    $prototype.find('.project-line-item-remove').on('click', function(e){
                        e.preventDefault();
                        $prototype.fadeOut('fast', function(){
                            $(this).remove();
                        });
                    });

                    $('#project-invoice-item-table tbody').append($prototype);
                });

                $modal.find('form').bind('submit', function(e){

                    self.setWorking(true);

                    $.ajax({
                        url : $(this).attr('action'),
                        method : 'POST',
                        data : $(this).serialize(),
                        complete: function(){
                            self.setWorking(false);
                        },
                        success : function(saveResponse) {
                            if (typeof saveResponse == 'object') {
                                if (typeof saveResponse.message != 'undefined') {
                                    self.alert(saveResponse.message);
                                }
                                return;
                            }

                            $modal.modal('toggle');

                            var $content = $(saveResponse);

                            $content.find(self.settings.selector.remove).on('click', function(e){
                                self.remove(e, this);
                            });

                            $content.find(self.settings.selector.edit).on('click', function(e){
                                self.edit(e, this);
                            });

                            self.element.find(self.settings.selector.list + ' tbody').append($content);

                        }
                    });
                    return false;
                });
            }
        });
    },

    /**
     * edit
     *
     * @param event
     * @param element
     */
    edit : function(event, element) {

    },
    /**
     * remove
     *
     * @param event
     * @param element
     */
    remove : function(event, element) {

    }

});


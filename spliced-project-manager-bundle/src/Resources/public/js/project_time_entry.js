
"use strict";

function SplicedProjectTimeEntry ( element, options ) {

    if (typeof element == 'undefined') {
        console.log('Could Not Find TimeEntry Wrapper');
        return;
    }

    this.element = $(element);

    this.settings = $.extend( {
        base_uri : false,
        selector : {
            list   : '#project-time-entry-table',
            add    : '.project-time-entry-new',
            edit   : '.project-time-entry-edit',
            remove : '.project-time-entry-remove'
        }
    }, options );
    this.init();
}

$.extend(SplicedProjectTimeEntry.prototype, SplicedProjectManagerBaseClass.prototype, {
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
            url : this.settings.base_uri + '/time_entry/new',
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
        var self = this;
        var timeEntryId = $(element).data('id');

        self.setWorking(true);

        $.ajax({
            url : this.settings.base_uri + '/time_entry/' + timeEntryId + '/edit',
            method : 'GET',
            complete: function(){
                self.setWorking(false);
            },
            success : function(editResponse) {
                if (typeof editResponse == 'object') {
                    if (typeof editResponse.message != 'undefined') {
                        self.alert(editResponse.message);
                    }
                    return;
                }

                var $modal = $(editResponse);

                $modal.modal({
                    backdrop : 'static',
                    keyboard : false
                }).on('hidden.bs.modal', function (e) {
                    $modal.remove();
                });

                $modal.find(self.settings.selector.remove).on('click', function(e){
                    $modal.modal('toggle');
                    self.remove(e, this);
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
                        success : function(updateResponse) {
                            if (typeof updateResponse == 'object') {
                                if (typeof updateResponse.message != 'undefined') {
                                    self.alert(updateResponse.message);
                                }
                                return;
                            }

                            $modal.modal('toggle');

                            var $content = $(updateResponse);

                            $content.find(self.settings.selector.remove).on('click', function(e){
                                self.remove(e, this);
                            });

                            $content.find(self.settings.selector.edit).on('click', function(e){
                                self.edit(e, this);
                            });

                            self.element.find('#project-time-entry-' + noteId ).replaceWith($content);

                        }
                    });
                    return false;
                });
            }
        });
    },
    /**
     * remove
     *
     * @param event
     * @param element
     */
    remove : function(event, element) {
        var self = this;

        if (!this.confirmAction('Confirm Deletion', 'Are you sure you want to remove this record?', {})) {
            return;
        }

        self.setWorking(true);

        $.ajax({
            url: this.settings.base_uri + '/time_entry/' + $(element).data('id') + '/remove',
            method: 'GET',
            complete: function () {
                self.setWorking(false);
            },
            success : function(response) {
                if (response.success) {
                    $('#project-time-entry-' + $(element).data('id')).fadeOut('slow', function(){
                        $(this).remove();
                    });
                }
            }
        });
    }

});


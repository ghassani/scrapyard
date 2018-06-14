"use strict";

function SplicedProjectFile ( element, options ) {

    if (typeof element == 'undefined') {
        console.log('Could Not Find File Wrapper');
        return;
    }

    this.element = $(element);

    this.settings = $.extend( {
        base_uri : false,
        selector : {
            list   : '#project-file-table',
            add    : '.project-file-new',
            edit   : '.project-file-edit',
            remove : '.project-file-delete'
        }
    }, options );
    this.init();
}

$.extend(SplicedProjectFile.prototype, SplicedProjectManagerBaseClass.prototype, {
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
     *
     * Handles all events related to adding a new file
     * to a project.
     *
     * @param event
     * @param element
     */
    add : function(event, element) {
        var self = this;

        $.ajax({
            url : this.settings.base_uri + '/file/new',
            method : 'GET',
            success : function(addResponse) {
                if (typeof addResponse == 'object') {
                    if (typeof addResponse.message != 'undefined') {
                        alert(addResponse.message);
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

                $modal.find('a.project-position-preset').on('click', function(e){
                    e.preventDefault();
                    $modal.find('#project_staff_position').val($(this).attr('rel'));
                });

                $modal.find('form').bind('submit', function(e){
                    $.ajax({
                        url : $(this).attr('action'),
                        method : 'POST',
                        data : $(this).serialize(),
                        success : function(saveResponse) {
                            if (typeof saveResponse == 'object') {
                                if (typeof saveResponse.message != 'undefined') {
                                    alert(saveResponse.message);
                                }
                                return;
                            }

                            $modal.modal('toggle');


                            var $content = $(saveResponse);

                            $content.find(self.settings.selector.remove_staff).on('click', function(e){
                                self.remove(e, this);
                            });

                            $('#project-staff-table tbody').append($content);

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
     * Handles all events related to editing a new file
     * to a project.
     *
     * @param event
     * @param element
     */
    edit : function(event, element) {

    },

    /**
     * remove
     *
     * Handles all events related to deleting a file
     * from a project.
     *
     * @param event
     * @param element
     */
    remove : function(event, element) {


    }

});


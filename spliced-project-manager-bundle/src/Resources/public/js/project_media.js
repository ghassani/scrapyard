"use strict";

function SplicedProjectMedia ( element, options ) {

    if (typeof element == 'undefined') {
        console.log('Could Not Find Media Wrapper');
        return;
    }

    this.element = $(element);

    this.settings = $.extend( {
        base_uri : false,
        selector : {
            list   : '#project-media-table',
            add    : '.project-media-new',
            edit   : '.project-media-edit',
            remove : '.project-media-remove'
        }
    }, options );
    this.init();
}

$.extend(SplicedProjectMedia.prototype, SplicedProjectManagerBaseClass.prototype, {
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


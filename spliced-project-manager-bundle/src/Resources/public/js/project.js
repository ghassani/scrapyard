"use strict";

function SplicedProject ( element, project, options ) {

    this.element = $(element);

    this.project = $.extend({}, project);

    this.settings = $.extend( {
        project_id : null,
        base_uri : false,
        selector : {
            remove : '.project-delete'
        }
    }, options );

    this.init();
}

$.extend(SplicedProject.prototype, SplicedProjectManagerBaseClass.prototype, {
    init: function () {

        var self = this;


        this.attributes = new SplicedProjectAttribute(document.getElementById('project-attribute-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.staff = new SplicedProjectStaff(document.getElementById('project-staff-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.files = new SplicedProjectFile(document.getElementById('project-file-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.media = new SplicedProjectMedia(document.getElementById('project-media-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.notes = new SplicedProjectNote(document.getElementById('project-note-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.invoices = new SplicedProjectInvoice(document.getElementById('project-invoice-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.timeEntries = new SplicedProjectTimeEntry(document.getElementById('project-time-entry-wrapper'), {
            base_uri : this.settings.base_uri
        });

        this.tabs = this.element.find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            self.setLastTab( $(e.target).attr('href'));
        });

        this.showLastTab();

    },


    setLastTab : function(tab) {
        $.cookie('last-project-tab-' + this.settings.project_id, tab);
        return this;
    },

    getLastTab : function() {
        if (!parseInt(this.settings.project_id)) {
            return false;
        }
        var lastTab = $.cookie('last-project-tab-' + this.settings.project_id);
        return this.element.find('a[href="' + lastTab + '"]');
    },

    showLastTab : function() {
        var $lastTab = this.getLastTab();
        if ($lastTab) {
            $lastTab.tab('show');
        }

    }

});


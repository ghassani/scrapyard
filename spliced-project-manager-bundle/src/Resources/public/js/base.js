"use strict";

var SplicedProjectManagerBaseClass = {
    prototype : {


        /**
         * confirmAction
         *
         * Get user confirmation for an action
         *
         * @param title
         * @param message
         * @param buttons
         * @returns {*}
         */
        confirmAction : function(title, message, buttons) {
            return confirm(title + ' - ' + message);
        },

        alert : function(message) {
            alert(message);
        },

        /**
         * setWorking
         *
         * @param bool state - True working, False not working
         */
        setWorking : function(state)
        {
            if (state == true) {
                $.blockUI();
            } else {
                $.unblockUI();
            }
        }

    }
};

$.extend(SplicedProjectManagerBaseClass.prototype, SplicedAdminThemeBaseClass.prototype);


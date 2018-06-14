/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

var SplicedAdminThemeBaseClass = {
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
            // $modal = $('<div class="modal fade in" id=" confirmationModal" tabindex="-1" role="dialog" aria-labelledby=" confirmationModalLabel" aria-hidden="false"><div class="modal-backdrop fade in" style="height: 1115px;"></div> <div class="modal-dialog modal-sm"> <div class="modal-content"> <form method="post" action=""> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> <h4 class="modal-title" id=" confirmationModalLabel">Confirm Deletion</h4> </div> <div class="modal-body">Are you sure? </div> <div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button> </div> </form> </div> </div> </div>');
            return confirm(title + ' - ' + message);
        },
        notify : function(title, message, buttons) {
            return confirm(title + ' - ' + message);
        },
        setLoading : function(){
            $.blockUI({});
        },
        clearLoading : function(){
            $.unblockUI();
        },
        log : function(e, e2) {
            if (this.settings.debug == true) {
                if (typeof e2 != 'undefined') {
                    return console.log(e,e2);
                }
                return console.log(e);
            }
        }
    }
};

/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedAdminList ( element, options ) {
    this.table = $(element);
    this.settings = $.extend( {
        add_row_selector : '.add-row',
        add_row_prototype_attribute : 'data-prototype',
        check_all_selector : 'input.check-all',
        no_records_message_class : 'no-result',
        batch_list_form : '#batch-form',
        debug : false
    }, options );
    this.init();
}

$.extend(SplicedAdminList.prototype, SplicedAdminThemeBaseClass.prototype, {
    /**
     *
     */
    init: function () {
        var self = this;
        this.add_row = this.table.find(this.settings.add_row_selector);
        this.check_all = this.table.find(this.settings.check_all_selector);
        if (this.add_row.length) {
            this.add_row.bind('click', function(e){
                self.addRow(this);
            });
        }
        if (this.check_all.length) {
            this.check_all.bind('change', function(e){
                self.checkAll(this);
            });
        }
        this.table.find('[data-action="batch-list"]').on('click', function(e){
            e.preventDefault();
            var action = $(this).data('batch-action');
            var confirmAction = $(this).data('confirm');
            if (!self.table.find('input[name="ids[]"]:checked').length) {
                alert('Nothing Selected');
                return;
            }
            if (!action) {
                alert('Invalid Action');
                return;
            }
            if (true == confirmAction) {
                if (!confirm('Are you sure?')){
                    return;
                }
            }
            $(self.settings.batch_list_form).find('#batch_action_action').val(action);
            $(self.settings.batch_list_form).trigger('submit');
        });
    },

    /**
     *
     * @param a
     */
    addRow: function(a) {
        var currentCount = this.table.find('tbody tr[class!="'+this.settings.no_records_message_class+'"]').length;
        var $prototype = $($(a).attr(this.settings.add_row_prototype_attribute).replace(/__name__/g, currentCount+1 ));
        if (typeof this.settings.onAddRow == 'function') {
            this.settings.onAddRow($prototype);
        }
        this.table.find('tbody').append($prototype);
        this.updateNoResult();
    },

    /**
     *
     */
    updateNoResult: function() {
        if(this.table.find('tbody tr[class!="'+this.settings.no_records_message_class+'"]').length) {
            this.table.find('tbody tr[class="'+this.settings.no_records_message_class+'"]').fadeOut('fast');
        } else if (!this.table.find('tbody.tr[class="'+this.settings.no_records_message_class+'"]').is(':visible')) {
            this.table.find('tbody tr[class="'+this.settings.no_records_message_class+'"]').fadeIn('fast');
        }
    },
    
    /**
     *
     * @param checkbox
     */
    checkAll: function(checkbox) {
        if($(checkbox).is(':checked')) {
            this.table.find('tbody input[type=checkbox]').attr('checked', 'checked');
        } else {
            this.table.find('tbody input[type=checkbox]').removeAttr('checked')
        }
    }
});

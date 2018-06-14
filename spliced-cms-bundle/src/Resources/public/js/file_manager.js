/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedFileManager ( element, options ) {
    this.element     = $(element);
    this.settings    = $.extend( {}, {
        debug : false,
        basePath : null,
        currentPath : null,
        baseDir : null,
        currentDir : null,
        selectors : {
            actionForm : '#action-form' // for actions which require CSRF form submissions to validate state such as delete
        },
        actions : {
            deleteFile : 'delete-file'
        }
    }, options );
    this.init();
}

$.extend(SplicedFileManager.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        self.element.find('[data-action="' + this.settings.actions.deleteFile + '"]').on('click', function(event){
           self.onDeleteFile(event, this);
        });
        this.log('SplicedFileManager Initialized', this.element);

    },
    /**
     *
     */
    onDeleteFile: function(event, element){
        var $element = $(element);
        if (!$element.data('href')) {
            this.log('No Target Set');
            return;
        }

        if (this.confirmAction('Are you sure?', 'Deleting this file can\'t be undone.', {})){
            window.location.href = $element.data('href');
        }
    }
});





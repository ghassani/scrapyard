/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedFileManagerEditFile ( element, options ) {
    this.element     = $(element);
    this.settings    = $.extend( {}, {
        debug : false,
        editor_id : 'file_content_ace'
    }, options );
    this.init();
}

$.extend(SplicedFileManagerEditFile.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;

        this.editor = new SplicedCmsEditor(document.getElementById(this.settings.editor_id), { debug : this.settings.debug });

        this.log('SplicedFileManagerEditFile Initialized', this.element);

    }
});





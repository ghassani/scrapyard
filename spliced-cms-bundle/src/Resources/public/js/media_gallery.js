/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

function SplicedMediaGallery( element, menu, options ) {
    this.element     = $(element);
    this.menu = menu;
    this.settings    = $.extend( {}, {
        debug : false
    }, options );
    this.init();
}

$.extend(SplicedMediaGallery.prototype, SplicedCmsBaseClass.prototype, {
    init: function () {
        var self = this;


        this.log('SplicedMediaGallery Initialized', this.element);
    }

});


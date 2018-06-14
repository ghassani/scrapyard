/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

"use strict";

$(document).ready(function($){
    var lists = $('table.spliced-admin-list').each(function(){
        var list = new SplicedAdminList(this, {
            add_row_selector : '.add-row',
            add_row_prototype_attribute : 'data-prototype',
            check_all_selector : 'input.check-all',
            no_records_message_class : 'no-result',
            debug : true
        });
    });
    $('[data-toggle="tooltip"]').tooltip({container:'body'});
    var toggleSlideOutLeftMenu = function() {
        if (!$('body').hasClass('slide-out-left-open')) {
            $('#slide-out-bar-left').animate({left: 0, opacity: 1 }, {
                duration: 250,
                queue: false,
                complete: function() {
                    if ($(this).find('.slide-out-wrapper').height() > $(window).height()) {
                        $(this).css('overflow-y', 'scroll');
                    }
                    $('body').addClass('slide-out-left-open');
                }
            });
            $('#page-wrapper').animate({left: "280px", opacity : 0.5}, {
                duration: 250,
                queue: false,
                complete : function(){
                 //   $(this).css('transform', 'rotate(7deg)');
                }
            });
        } else {
            // reset pinned status
            $('body').removeClass('slide-out-left-pinned').removeClass('slide-out-right-pinned');
            $('#slide-out-bar-left').animate({left: "-280px", opacity : 0}, {
                duration: 250,
                queue: false,
                complete: function() {
                    $('body').removeClass('slide-out-left-open');
                }
            });
            $('#page-wrapper').animate({left: 0, opacity : 1 }, {
                duration: 250,
                queue: false,
                complete : function(){
                 //   $(this).css('transform', 'rotate(0deg)');
                }
            });
        }
    };

    var toggleSlideOutRightMenu = function() {
        if (!$('body').hasClass('slide-out-right-open')) {
            $('#slide-out-bar-right').animate({right: 0, opacity: 1 }, {
                duration: 250,
                queue: false,
                complete: function() {
                    if ($(this).find('.slide-out-wrapper').height() > $(window).height()) {
                        $(this).css('overflow-y', 'scroll');
                    }
                    $('body').addClass('slide-out-right-open');
                }
            });
            $('#page-wrapper').animate({right: "280px", opacity : 0.5}, {
                duration: 250,
                queue: false,
                complete : function(){
                  //  $(this).css('transform', 'rotate(-7deg)');
                }
            });
        } else {
            // reset pinned status
            $('body').removeClass('slide-out-left-pinned').removeClass('slide-out-right-pinned');
            $('#slide-out-bar-right').animate({right: "-280px", opacity : 0}, {
                duration: 250,
                queue: false,
                complete: function() {
                    $('body').removeClass('slide-out-right-open');
                }
            });
            $('#page-wrapper').animate({right: 0, opacity : 1 }, {
                duration: 250,
                queue: false,
                complete : function(){
                   // $(this).css('transform', 'rotate(0deg)');
                }
            });
        }
    };

    $("a.toggle-slide-out-left").on('click', function(e){
        e.preventDefault();
        toggleSlideOutLeftMenu();
    });
    
    $("a.toggle-slide-out-right").on('click', function(e){
        e.preventDefault();
        toggleSlideOutRightMenu();
    });

    $("a.pin-slide-out-left").on('click', function(e){
        e.preventDefault();
        if (!$('body').hasClass('slide-out-left-pinned')) {
            $('body').addClass('slide-out-left-pinned');
        } else {
            $('body').removeClass('slide-out-left-pinned');
        }
    });

    $("a.pin-slide-out-right").on('click', function(e){
        e.preventDefault();
        if (!$('body').hasClass('slide-out-right-pinned')) {
            $('body').addClass('slide-out-right-pinned');
        } else {
            $('body').removeClass('slide-out-right-pinned');
        }
    });

    $('#page-wrapper').on('click', function(e){
        if ($('body').hasClass('slide-out-left-open') && e.pageX > 280) {
            toggleSlideOutLeftMenu();
        }
        if ($('body').hasClass('slide-out-right-open') && e.pageX > 768) {
            toggleSlideOutRightMenu();
        }
    });

    /*
    * Slide Out Menu
     */
    $('.slide-out-nav a.dropdown-toggle').on('click', function(e){
        var $childMenu = $(this).parent().find('ul');
        if(!$childMenu.length) {
            console.log('Child Menu Not Found');
            return false;
        }
        $childMenu = $childMenu.first();
        if ($childMenu.is(':visible')) {
            $childMenu.slideUp(500, function(){
            });
        } else {
            $childMenu.slideDown(500, function(){
            });
        }
    });
});

/*
* This file is part of the SplicedCmsBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

$(document).ready(function($){


    if (typeof Switchery != undefined) {
        $('.js-switch').each(function(){
            new Switchery(this);
        });
    }

    $('a.content-page-add-meta').bind('click', function(e){
       var prototype = $(this).data('prototype');

        
    });



    $("a.add-menu-item-row").on('click', function(e){
        $table = $('#menu-items-table');

        var currentCount = $table.find('tbody tr[class!="main-no-result"]').length;

        var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, currentCount+1 ));

        $table.find('tbody.main').append($prototype);
        if($table.find('tbody.main tr[class!="main-no-result"]').length) {
            $table.find('tbody.main tr[class="main-no-result"]').fadeOut('fast');
        } else if (!$table.find('tbody.tr[class="main-no-result"]').is(':visible')) {
            $table.find('tbody.main tr[class="main-no-result"]').fadeIn('fast');
        }

    });



    $('[data-action="form-submit"]').on('click', function(e){
        e.preventDefault();

        if(!$(this).data('target')) {
            return;
        }

        $target = $($(this).data('target'));

        if (!$target.length) {
            return;
        }

        $target.trigger('submit');
    });

    /*$( document ).ajaxStart(function() {
        $.blockUI({});
    }).ajaxStop(function() {
        $.unblockUI();
    }).ajaxError(function( event, jqxhr, settings, thrownError ) {
        alert(thrownError);
    });
    */


    //setTimeout(function(){

        $.ajax({
            url : '/dev.php/admin/history/add',
            method : 'POST',
            dataType : 'json',
            global : false,
            data : {
                url : window.location.href,
                name : $('head title').text()
            },
            success : function(response) {
                if (response.success) {
                    $("ul.history-list").prepend($('<a href="'+ response.data[0].url +'">' + response.data[0].name +'</a>'));
                }
            }
        });

    //}, 5000);
});





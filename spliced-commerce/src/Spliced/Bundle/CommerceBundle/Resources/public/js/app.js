/**
 * General Application Javascript Events and Functions
 */

if(window.location.href.match(/frontend_dev\.php/)){
	var XHR_BASE_URL = '/frontend_dev.php';
} else if(window.location.href.match(/combined_dev\.php/)){
	var XHR_BASE_URL = '/combined_dev.php';
} else {
	var XHR_BASE_URL = '';
}
var blockUiMessage = '<h1>Just a moment...</h1>'; 

var logger;

$(document).ready(function(){


	logger = new AjaxLogger({uri:XHR_BASE_URL+'/logger/log/javascript-error'});
	
	//$("nav.top_menu ul li.parent").topDropMenu();
	
	//$('nav.top_menu').affix();

	if($('.ad-rotator').length){
		$('.ad-rotator').advertisementRoller();
	}

	$('input, textarea').placeholder();
	$('select.selectpicker').selectpicker();
	
	/**
	 * 
	 */
	$('.btn-radio button').live('click', function(e){
		$('#'+$(this).attr('data-target')).val($(this).attr('data-value'));
	});
	
	/**
	 * 
	 */
	$('a.category_expand_tier_data').click(function(e){
		e.preventDefault();
		$(this).parent().find('.hide').fadeIn();
	});


	/**
	 * 
	 */
	$('a.ajax_link,button.ajax_link').live('click', function(e){
		e.preventDefault();
		$link = $(this).attr('href');
		$.ajax({
			type: "GET",
			url: $link,
			dataType : 'json',
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				var responseHandler = new AjaxResponseHandler(this,response,{
					modalShown : function(){
						$(".modal-footer button").click(function(e){
							if($(this).attr('data-href')){
								window.location = $(this).attr('data-href');
							}
						});
					}
				});
			},
			error: function() {
				window.location.href = $link;
			}
		});
	});
	
	/**
	 * Ajax Complete/Stop Event Hooks
	 */

	$(document).bind("ajaxSend", function(){
		
	}).bind("ajaxComplete", function(){
		$('input, textarea').placeholder();
		$('select.selectpicker').selectpicker()
	}).bind("ajaxStart", function(){
		$.blockUI({ message: blockUiMessage });
	}).bind("ajaxStop", function(){
		$.unblockUI();
	});
	
}); 
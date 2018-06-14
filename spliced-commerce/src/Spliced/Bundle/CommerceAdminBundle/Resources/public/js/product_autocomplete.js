(function($) {

    $.fn.productAutocomplete = function( _options ) {

        var options = $.extend({

        }, _options);

		if(options.target == undefined){
			console.log('Target wrapper is undefined or not found and is required to initialize a product autocomplete');
			console.log(options.target);
			return;
		}
		
		var $target = $(options.target);
		alert(options.target);
		$(this).blur(function(e){
			$(document).queue("productAutocompleteQueue", function(){
				$.ajax({
					type: "GET",
					url: XHR_BASE_URL + '/product/ajax-search',
					dataType : 'json',
					data: { q : $(this).val() },
					beforeSend: function(){
						//$.blockUI({ message: blockUiMessage });
					},
					success: function(response) {
						
						if(response.products.length){
							$list = $('<ul></ul>');
							$.each(response.products, function(i, product) {
								$list.append($('<li>'+product.name+'</li>'));
							});
							console.log(options.target.class);
							$target.empty().append($list);
						} else {
							
						}
						//$.unblockUI();				
					},
					error: function() {
						//$.unblockUI();
						alert('An Error Occoured');
					},
				});
			});
		});
    }
}(jQuery));
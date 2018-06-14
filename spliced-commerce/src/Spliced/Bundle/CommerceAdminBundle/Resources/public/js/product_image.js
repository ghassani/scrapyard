$(document).ready(function(){

	/**
	 * Add a Image From Prototype
	 */
	$('a.add-product-image').click(function(e){
		e.preventDefault();
		var $target = $("table.product-image-table tbody");
		var $noResultsHolder = $('table.product-image-table tbody tr.no-results');
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.find('.main-body').length));
		
		$prototype.find('a.delete-product-image').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if(!$('table.product-image-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
						$noResultsHolder.removeClass('hide').fadeIn('fast', function(){
								
						});
					}
				});
			}		
		}); 
		
		if($noResultsHolder.is(":visible")){
			$noResultsHolder.fadeOut('fast', function(){
				$target.append($prototype);
			});
		} else {			
			$target.append($prototype);
		}
	});
	
	/**
	 * Delete Product Price Tier
	 */
	$('a.delete-product-image').click(function(e){
		e.preventDefault();
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		var $noResultsHolder = $('table.product-image-table tbody tr.no-results');

		$.ajax({
			type: "POST",
			url: $(this).attr('href'),
			dataType : 'json',
			data: { },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
	
				if(response.success == true){
					var $target = $("tr.product-image-"+response.image_id);
										
					$target.fadeOut('slow', function(){
						$target.remove();
						
						if(!$('table.product-image-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
							$noResultsHolder.removeClass('hide').fadeIn('fast', function(){
								
							});
						}
					});
				} else if(response.message !== undefined && response.message.length){
					alert(response.message);
				}
			},
			error: function() {
				$.unblockUI();
			},
		});
	});
});
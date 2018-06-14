$(document).ready(function(){

	/**
	 * Add a Tier Price Value From Prototype
	 */
	$('a.add-product-price-tier').click(function(e){
		e.preventDefault();
		var $target = $("table.product-price-tier-table tbody");
		var $noResultsHolder = $('table.product-price-tier-table tbody tr.no-results');
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.find('.main-body').length));
		
		$prototype.find('a.delete-product-price-tier').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if(!$('table.product-price-tier-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
	$('a.delete-product-price-tier').click(function(e){
		e.preventDefault();
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		var $target = $($(this).attr('data-target'));
		var $noResultsHolder = $('table.product-price-tier-table tbody tr.no-results');

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
					var $target = $("tr.tier-price-"+response.tier_price_id);
					
					$target.fadeOut('slow', function(){
						$target.remove();
						
						if(!$('table.product-price-tier-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
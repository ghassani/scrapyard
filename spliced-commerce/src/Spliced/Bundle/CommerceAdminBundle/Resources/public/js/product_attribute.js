$(document).ready(function(){


	/**
	 * Add a Product Attribute by Prototype
	 */
	$('a.add-product-attribute').click(function(e){
		e.preventDefault();
		var $target = $("table.product-attributes-table tbody");
		var $noResultsHolder = $('table.product-attributes-table tbody tr.no-results');
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.find('.main-body').length));
		
		$prototype.find('select[data-group="option"]').change(function(e){
			if(!$(this).val()){
				return;
			}
			$.ajax({
				type: "GET",
				url: XHR_BASE_URL+'/product-attribute-option/get-option/'+$(this).val(),
				dataType : 'json',
				data: { },
				beforeSend: function(){
					$.blockUI({ message: blockUiMessage });
				},
				success: function(option) {
					$.unblockUI();
					
					if(option.error == undefined){
						
						$prototype.find('.attribute-description').empty().append($('<small>'+option.description+'</small>'));
					}
				},
				error: function() {
					$.unblockUI();
				},
			});
		});
		
		
		$prototype.find('a.delete-product-attribute').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if(!$('table.product-attributes-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
	 * Delete Product Attribute
	 */
	$('a.delete-product-attribute').click(function(e){
		e.preventDefault();
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		var $noResultsHolder = $('table.product-attributes-table tbody tr.no-results');

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
					var $target = $("tr.product-attribute-"+response.attribute_id);
										
					$target.fadeOut('slow', function(){
						$target.remove();
						
						if(!$('table.product-attributes-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
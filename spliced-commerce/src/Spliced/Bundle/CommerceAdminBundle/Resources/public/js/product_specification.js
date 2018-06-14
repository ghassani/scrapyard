$(document).ready(function(){


	/**
	 * Add a Product Specification by Prototype
	 */
	$('a.add-product-specification').click(function(e){
		e.preventDefault();
		var $target = $("table.product-specifications-table tbody");
		var $noResultsHolder = $('table.product-specifications-table tbody tr.no-results');
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.find('.main-body').length));
		
		$prototype.find('select[data-group="option"]').change(function(e){
			if(!$(this).val()){
				return;
			}
			$.ajax({
				type: "GET",
				url: XHR_BASE_URL+'/product-specification-option/get-option/'+$(this).val(),
				dataType : 'json',
				data: { },
				beforeSend: function(){
					$.blockUI({ message: blockUiMessage });
				},
				success: function(option) {
					$.unblockUI();
					
					if(option.error == undefined){
						
						$target = $prototype.find('select[data-group="values"]');
						$target.removeAttr('disabled');
							
						if(option.optionType == 2){
							$target.find('option').remove();
							$target.attr('multiple', true);
						} else {
							$target.find('option[value!=""]').remove();
							$target.find('option[value=""]').html('-Select a Value -')
							$target.removeAttr('multiple');
							if(!$target.find('option[value=""]').length){
								$target.append($('<option value="">-Select a Value -</option>'));
							}
						}
												
							
						$(option.values).each(function(i,e){
							$target.append($('<option value="'+e.value+'">'+e.value+'</option>'));
						});
					}
				},
				error: function() {
					$.unblockUI();
				}
			});
		});
		
		
		$prototype.find('a.delete-product-specification').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if(!$('table.product-specifications-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
	 * Delete Product Specification
	 */
	$('a.delete-product-specification').click(function(e){
		e.preventDefault();
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		var $noResultsHolder = $('table.product-specifications-table tbody tr.no-results');

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
					var $target = $("tr.product-specification-"+response.specification_id);
										
					$target.fadeOut('slow', function(){
						$target.remove();
						
						if(!$('table.product-specifications-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
	});0
});
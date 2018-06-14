$(document).ready(function(){

	$('a.add-product-bundled-item').click(function(e){
		e.preventDefault();

		var $target = $("table.product-bundled-items tbody");
		var $noResultsHolder = $('table.product-bundled-items tbody tr.no-results');
		var $rowId = $target.find('.main-body').length;
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $rowId));
		
		$prototype.find('input.product-bundled-item-typeahead').typeahead({
			name: 'products',
			remote: XHR_BASE_URL + '/product/ajax-search?q=%QUERY',
			engine: TypeaheadTemplateEngine,
			template: '<div class="typeahead-result-row">{{name}} - <i>{{sku}}</i></div>'
		}).on('typeahead:selected', function (object, datum) {
			
			$html = $('<div class="pull-left">' +
	        	'<img src="'+datum.thumbnail+'" />' +
	          '</div>'+
	          '<div><i>'+datum.name+'</i> - SKU: '+datum.sku+' </div>' +
	          '<div><strong>Price:</strong> $'+parseInt(datum.price).toFixed(2)+'</div>');
			
			$prototype.find('.bundled-item-product-info-'+$rowId).empty().append($html);
			
		});
		
		
		$prototype.find('a.delete-product-bundled-item').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if(!$('table.product-bundled-items tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
	$('a.delete-product-bundled-item').click(function(e){
		e.preventDefault();
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		var $noResultsHolder = $('table.product-bundled-items tbody tr.no-results');

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
					var $target = $("tr.product-bundled-item-"+response.bundled_item_id);
										
					$target.fadeOut('slow', function(){
						$target.remove();
						
						if(!$('table.product-bundled-items tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
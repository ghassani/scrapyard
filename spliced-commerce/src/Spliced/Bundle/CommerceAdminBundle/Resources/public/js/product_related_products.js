$(document).ready(function(){

	$('a.add-related-product').click(function(e){
		e.preventDefault();

		var $target = $("table.product-related-products-table tbody");
		var $noResultsHolder = $('table.product-related-products-table tbody tr.no-results');
		var $rowId = $target.find('.main-body').length;
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $rowId));
		
		$prototype.find('input.related-product-typeahead').typeahead({
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
			
			$prototype.find('.related-product-info-'+$rowId).empty().append($html);
			
		});
		 
		
		$prototype.find('a.delete-product-related-product').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if(!$('table.product-related-products-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
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
	
	$('a.delete-related-product').click(function(e){
		e.preventDefault();
		if(confirm("Are you sure?")){
			var $target = $($(this).attr('data-target'));
			var $noResultsHolder = $('table.product-related-products-table tbody tr.no-results');
	
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
						$target.fadeOut('slow', function(){
							$target.remove();
							
							if(!$('table.product-related-products-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
								$noResultsHolder.removeClass('hide').fadeIn('fast', function(){
									
								});
							}
						});
					}
				},
				error: function() {
					$.unblockUI();
					alert('An Error Occoured');
				},
			});
			/*$prototype.fadeOut('fast', function(){
				$prototype.remove();
				if(!$('table.product-related-products-table tbody tr.main-body').length && !$noResultsHolder.is(':visible')){
					$noResultsHolder.removeClass('hide').fadeIn('fast', function(){
							
					});
				}
			});*/
		}		
	}); 
});
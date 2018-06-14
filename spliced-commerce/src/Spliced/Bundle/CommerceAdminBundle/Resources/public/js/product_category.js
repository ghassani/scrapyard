$(document).ready(function(){
		
	$('a.add-product-category').click(function(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: $(this).attr('href'),
			dataType : 'json',
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				var responseHandler = new AjaxResponseHandler(this, response,{
					modalShown: function(){
						$("form.add-product-category-form").submit(function(e){
							e.preventDefault();
							$form = $(this);
							
							$.ajax({
								type: "POST",
								url: $form.attr('action'),
								dataType : 'json',
								data: $form.serialize(),
								beforeSend: function(){
	
								},
								success: function(response) {
									if(response.success){
										$("#addProductCategory").on('hidden.bs.modal', function () {
											$("#addProductCategory").remove();
											$('.modal-backdrop').remove();
											$("table.product-category-table tbody").append($(unescape(response.html)));
										}).modal('hide');
									} else {
										$("#addProductCategory .modal-body").empty().append($(unescape(response.html)));
									}
								},
								error: function(){
									alert('An unexpected error has occurred');
								}
							});
		
							return false;
						});
					}
				});
			},
			error: function() {
				alert('An Error Occoured');
			},
		});
	});
	
	$("a.delete-product-category").live('click', function(e){
		e.preventDefault();
		
		if(!confirm('Are you sure?')){
			return false;
		}
		
		$.ajax({
			type: "GET",
			url: $(this).attr('href'),
			dataType : 'json',
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			complete: function(response){
				$.unblockUI();
				if(response.success){
					$noResultsHolder = $("table.product-category-table tr.no-results");
					
					$target = $('tr.product-category'+response.category_id);

					$target.fadeOut('fast', function(){
						$target.remove();
						$totalRows = $("table.product-category-table tr.main-body").length;
						if(!$totalRows && ($noResultsHolder.hasClass('hide') || !$noResultsHolder.is(':visible'))){
							$noResultsHolder.removeClass('hide').fadeIn('fast');
						}
					});
					
				} else {
					alert('An Error Occoured');
				}
			}
		});
	});
});

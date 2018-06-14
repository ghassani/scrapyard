$(document).ready(function(){
	
	var formatProductUrlSlugString = function(str)
	{
		return str.replace(' ','-');
	}
	/**
	 * Checks Input Field If Sku Already Exists For New Product
	 */
	$('input.new-sku-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val()){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/product/check-sku',
			dataType : 'json',
			data: { sku : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false){
					alert('SKU Already Exists With ID '+response.id);
					input.val('');
				}
			},
			error: function() {
				$.unblockUI();
				alert('An Error Occoured');
			},
		});
	});
	

	/**
	 * Checks Input Field If Sku Already Exists For Existing Product
	 */
	$('input.edit-sku-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val() || input.val() == input.attr('data-original')){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/product/check-sku',
			dataType : 'json',
			data: { sku : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false && input.attr('data-id') != response.id){
					alert('SKU Already Exists With ID '+response.id);
					input.val(input.attr('data-original'));
				}
			},
			error: function() {
				$.unblockUI();
				alert('An Error Occoured');
			},
		});
	});
	
	

	/**
	 * 
	 */
	$('input.product-url-slug-auto-format').change(function(e){
		e.preventDefault();
		$(this).val(formatProductUrlSlugString($(this).val()));
	});

	/**
	 * Checks Input Field If Route Already Exists
	 */
	$('input.new-product-url-slug-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val()){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/product/check-url-slug',
			dataType : 'json',
			data: { slug : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false){
					alert('Product URL Slug Already Exists With ID '+response.id);
					input.val('');
				}
			},
			error: function() {
				$.unblockUI();
				alert('An Error Occoured');
			},
		});
	});
	
	/**
	 * 
	 */
	$('input.edit-product-url-slug-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val() || input.val() == input.attr('data-original')){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/product/check-url-slug',
			dataType : 'json',
			data: { slug : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false && input.attr('data-id') != response.id){
					alert('Product URL Slug Already Exists With ID '+response.id);
					input.val(input.attr('data-original'));
				}
			},
			error: function() {
				$.unblockUI();
				alert('An Error Occoured');
			},
		});
	});	
	
});
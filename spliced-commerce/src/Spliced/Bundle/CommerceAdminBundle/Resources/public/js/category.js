$(document).ready(function(){
	
	var formatCategoryUrlSlugString = function(str)
	{
		return str.replace(' ','-');
	}
	
	$('.category-selection-list a').live('click', function(e){
	 	e.preventDefault();
		
		$target = $("#category-edit-ajax-content");
		
		if(!$target.length){
			console.log('Target Element Not Found');
			return;
		}
		
		$.ajax({
			type: "GET",
			url: $(this).attr('href'),
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			complete: function(response){
				$.unblockUI();
				$target.fadeOut('fast', function(){
					$(this).empty().append($(response.responseText));
					$(this).fadeIn('fast', function(){
						
					});
				});
			}
		});
	});
	
	/**
	 * 
	 */
	$('input.category-url-slug-auto-format').change(function(e){
		e.preventDefault();
		$(this).val(formatCategoryUrlSlugString($(this).val()));
	});

	/**
	 * Checks Input Field If Route Already Exists
	 */
	$('input.new-category-url-slug-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val()){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/category/check-url-slug',
			dataType : 'json',
			data: { slug : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false){
					alert('Category URL Slug Already Exists With ID '+response.id);
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
	$('input.edit-category-url-slug-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val() || input.val() == input.attr('data-original')){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/category/check-url-slug',
			dataType : 'json',
			data: { slug : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false && input.attr('data-id') != response.id){
					alert('Category URL Slug Already Exists With ID '+response.id);
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

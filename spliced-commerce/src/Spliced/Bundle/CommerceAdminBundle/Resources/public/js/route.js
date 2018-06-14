$(document).ready(function(){
	
	var formatRouteString = function(str)
	{
		return str.replace(' ','-');
	}
	
	/**
	 * 
	 */
	$('input.route-auto-format').change(function(e){
		e.preventDefault();
		$(this).val(formatRouteString($(this).val()));
	});
	
	/**
	 * Checks Input Field If Route Already Exists
	 */
	$('input.new-route-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val()){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/route/check-route',
			dataType : 'json',
			data: { path : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false){
					alert('Route Already Exists With ID '+response.id);
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
	$('input.edit-route-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val() || input.val() == input.attr('data-original')){
			return;
		} 

		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/route/check-route',
			dataType : 'json',
			data: { path : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false && input.attr('data-id') != response.id){
					alert('Route Already Exists With ID '+response.id);
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
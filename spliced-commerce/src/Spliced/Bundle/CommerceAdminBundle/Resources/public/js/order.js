$(document).ready(function(){
	
	$('a.add-order-shipment-memo').click(function(e){
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
				});
			},
			error: function() {
				alert('An Error Occoured');
			},
		});
	});
	
	$('a.add-order-payment-memo').click(function(e){
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
				});
			},
			error: function() {
				alert('An Error Occoured');
			},
		});
	});
});
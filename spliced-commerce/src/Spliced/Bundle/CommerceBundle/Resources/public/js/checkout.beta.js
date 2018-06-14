function CheckoutFormHandler(form, options) {
	this.options	= options;
	this.debug 		= true; /* SET TO FALSE WHEN PRODUCTION */
	this.form		= $(form);
	
	this.events = {
		initialize: function(){
			this.log('Initialize Event Called');
			
   			if(this.options.initialize){
   				this.options.initialize.call(this);
   			}
		}
	};
	
	this.initialize();
};
	 
CheckoutFormHandler.prototype.initialize = function() {
	this.events.initialize.call(this);
	
	this.form.live('submit', function(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: XHR_BASE_URL+"/checkout",
			data: $(this).serialize(),
			dataType : 'json',
			async: false,
			cache: false,
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
				$("#checkout_payment_creditCard_cardNumber").val('');
				$("#checkout-continue-button, #checkout-process-button, #checkout-back-button,#checkout-cancel-button,#checkout-edit-button").attr("disabled", "disabled");
			},
			success: function(response, textStatus, jqXHR) {
				var responseHandler = new AjaxResponseHandler(this,response,{
					modalShown : function(){
						$("#checkout-continue-button, #checkout-process-button, #checkout-back-button,#checkout-cancel-button,#checkout-edit-button").removeAttr("disabled");
					}
				});				
			},
			error: function(jqXHR, textStatus, errorThrown ) {
				$("#checkout-continue-button, #checkout-process-button, #checkout-back-button,#checkout-cancel-button,#checkout-edit-button").removeAttr("disabled");

				logger.log('There was an error During Checkout Step Progression',{
					'details':{ 
						'textStatus': textStatus, 
						'errorThrown' : errorThrown,												
						'responseText' : jqXHR.responseText,
					}
				});
			},
		});
	});
	
};

CheckoutFormHandler.prototype.log = function(message) {
	if(this.debug){
		console.log(message);
	}
};
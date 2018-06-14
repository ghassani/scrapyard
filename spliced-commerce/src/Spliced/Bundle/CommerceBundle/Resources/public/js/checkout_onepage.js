/**
 * CheckoutForm
 * 
 * Checkout form for One Page Checkout
 */
function CheckoutForm(form, options) {	
	self = this;
	
	this.options = {
		debug : false,
		requiredShippingRatesSelector : '.required-for-shipping-rates',
		shippingRateRequestUrl : '/checkout/get-shipping-methods',
		billingToShippingSelector : '.set-shipping-to-billing'
	};
	
	if(typeof options != 'undefined'){
    	$.extend(this.options, options);
    }
	
    this.form = form instanceof jQuery ? form : $(form);
		
	if(!this.form.length){
		this.log('Form Not Found');
		return;
	}
	
	if(typeof $ == 'undefined'){
		if(typeof jQuery != 'undefined'){
			$ = jQuery;
		} else {
			this.log('jQuery is Required');
			return;
		}
	}
	        
	this.initialize();
};
	 
/**
 * 
 */
CheckoutForm.prototype.initialize = function() {
	self = this;
	
	this.form.on('submit', this.submit);	
	this.form.find(this.options.requiredShippingRatesSelector).on('change', this.shippingInformationChange);
	this.form.find(this.options.billingToShippingSelector).on('click', this.billingToShipping);
	
	if(this.shippingInformationValid()){
		this.getShippingRates();
	}
};

/**
 * 
 */
CheckoutForm.prototype.submit = function(e) {
	var isValid = true;
	self.form.find('input, select, textarea').each(function(i, input){
		$input = $(input);
		if($input.hasAttr('required') && $input.attr('required') == 'required'){
			$input.parent('form-group').addClass('has-error');
			isValid = false;
		}
	});
	
	return isValid;
};

/**
 * 
 */
CheckoutForm.prototype.shippingInformationChange = function(e) {
	if(self.shippingInformationValid()){
		self.log('Valid Shipping Fields');
		
		self.getShippingRates();
	} else {
		self.log('Invalid or Missing Required Shipping Fields');
	}
};

/**
 * 
 */
CheckoutForm.prototype.shippingInformationValid = function() {
	var hasValidShippingInformation = true;
	
	this.form.find(this.options.requiredShippingRatesSelector).each(function(i, input){
		if(!$(input).val()){
			hasValidShippingInformation = false;
		}
	});
	
	return hasValidShippingInformation;
};

/**
 * 
 */
CheckoutForm.prototype.getShippingRates = function() {
	data = this.form.find(this.options.requiredShippingRatesSelector).serialize().replace()
	$.ajax(this.form.attr('action'), {
		type : 'POST',
		data : this.form.serialize() + '&action=update-shipping', 
		dataType : 'html',
		success : function(response){
			var $response = $(response);
			var $newMethods = $response.find('#shipping-methods');
			var currentMethod = $("#shipping-methods").find('input:checked').val();
			if($newMethods.length){
				$("#shipping-methods").replaceWith($newMethods);
				
				if(typeof currentMethod != 'undefined' && currentMethod.length){
					$("#shipping-methods").find('input[value='+currentMethod+']').attr('checked', 'checked');
				} 
			}
		},
		error : function(){
			
		}
	});
};

CheckoutForm.prototype.billingToShipping = function(e){
	e.preventDefault();
	$("#checkout_shippingAddress").val($("#checkout_billingAddress").val());
	$("#checkout_shippingAddress2").val($("#checkout_billingAddress2").val());
	$("#checkout_shippingFirstName").val($("#checkout_billingFirstName").val());
	$("#checkout_shippingLastName").val($("#checkout_billingLastName").val());
	$("#checkout_shippingCompany").val($("#checkout_billingCompany").val());
	$("#checkout_shippingCity").val($("#checkout_billingCity").val());
	$("#checkout_shippingState").val($("#checkout_billingState").val());
	$("#checkout_shippingZipcode").val($("#checkout_billingZipcode").val());
	$("#checkout_shippingPhoneNumber").val($("#checkout_billingPhoneNumber").val());
	$("#checkout_shippingCountry").val($("#checkout_billingCountry").val());
}

/**
 * 
 */
CheckoutForm.prototype.log = function(message) {
	if(this.options.debug){
		console.log(message);
	}
};

/**
 * 
 */
$(document).ready(function($){
	if($('#form-checkout').length){
		checkoutForm = new CheckoutForm($('#form-checkout'), {
			debug : true,
			shippingRateRequestUrl : '/frontend_dev.php/checkout/get-shipping-methods'
		});
	}
});

/**
 * ajaxForm 
 * 
 * Usage:
 * 
 * $("#form-id, .form-class").ajaxForm({
 * 		responseType : 'json',
 *  	beforeSend : function(form, jqXHR, settings){},
 *  	success : function(form, response, textStatus, jqXHR){},
 *  	error : function(form, jqXHR, textStatus, errorThrown){},
 *  	complete : function(form, jqXHR, textStatus){},
 *  	validate : function(form){ return true; },
 * });
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
;(function(defaults, $, window, document, undefined) {
	'use strict';
	$.fn.extend({
		options : {
			// disables built in validation
			// and calls only user function validate
			userValidationOnly : false,
			global: true,
			// ajax options
			responseType : 'json',
			// events
			validate 	: null,
			beforeSend  : null,
			success 	: null,
			error 		: null,
			complete 	: null			
		},
		ajaxForm : function(options) {
			options = $.extend(this.options, options);	
			return $(this).each(function() {
				self = $(this);
				self.options = options;				
				
				self.on('submit', function(e){
					e.preventDefault();
					return self.submit(e);
				});
			});
		},
		submit : function(e){	
			self = $(this);
			
			if(!self.validate()){
				return false;
			}		
						
			$.ajax({
				type: self.attr('method').length ? self.attr('method') : 'POST',
				url:  self.attr('action'),
				data: self.serialize(),
				dataType : self.options.responseType,
				global: self.options.global,
				beforeSend: function(jqXHR, settings){
					if(typeof self.options.beforeSend == 'function'){
						return self.options.beforeSend.call(self, jqXHR, settings);
					}
				},
				success: function(response, textStatus, jqXHR) {
					if(typeof self.options.success == 'function'){
						return self.options.success.call(this, self, response, textStatus, jqXHR);
					}
				}, 
				error: function(jqXHR, textStatus, errorThrown) {
					if(typeof self.options.error == 'function'){
						return self.options.error.call(self, jqXHR, textStatus, errorThrown);
					}
				},
				complete : function(jqXHR, textStatus){
					if(typeof self.options.complete == 'function'){
						return self.options.complete.call(self, jqXHR, textStatus);
					}	
				}
			});
		},
		validate : function(){
			if(this.options.userValidationOnly == true){
				if(typeof this.options.validate == 'function'){
					return this.options.validate.call(this, true);
				}
				return true;
			}
			
			var isValid = true;
			
			$(this).find('input,select,textarea').each(function(i, input){
				var inputIsError = false;
				input = $(input);			
				
				if(input.hasClass('required') && ! input.val().length ) {
					inputIsError = true;
				}
				
				if(inputIsError){
					isValid = false;
					
					if(input.parent('.form-group').length){
						input.parent('.form-group').addClass('has-error');
					} else {
						input.addClass('has-error');
					}	
								
					input.on('blur', function(e){
						if($(this).val()){
							if($(this).parent('.form-group').length){
								$(this).parent('.form-group').removeClass('has-error');
							} else {
								$(this).removeClass('has-error');
							}	
							$(this).unbind('blur');
						}
					});
				}
			});
	
			return isValid;
		}
	});
})({}, jQuery, window, document);
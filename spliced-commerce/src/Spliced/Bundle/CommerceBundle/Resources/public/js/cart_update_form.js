/**
 * cartUpdateForm
 * 
 * Usage:
 * 
 * $("#form-id, .form-class").cartUpdateForm({});
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
;(function(defaults, $, window, document, undefined) {
	'use strict';
	
	$.fn.extend({
		options : {
			quantity_selector : 'input.quantity'
		},
		cartUpdateForm : function(options) {
			options = $.extend(this.options, options);
			return $(this).each(function() {
				self = $(this);
				self.options = options;
				
				self.on('submit', function(e){
					return self.submit(e);
				});
			});
		},
		submit : function(e) {
			var self = $(this);
			var isValid = true;
			
			$(this).find(this.options.quantity_selector).each(function(i, qty){
				qty = $(qty);
				
				if(!qty.val()){
					if(qty.parent('.form-group').length){
						qty.parent('.form-group').addClass('has-error');
					} else {
						qty.addClass('has-error');
					}	
					if(isValid){
						qty.focus();
					}
					isValid = false;
					
					qty.on('blur', function(e){
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
		},
		revalidate : function() {
			
		}
	});
})({}, jQuery, window, document);
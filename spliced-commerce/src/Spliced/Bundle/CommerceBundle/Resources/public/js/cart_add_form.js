/**
 * cartAddForm 
 * 
 * Usage:
 * 
 * $("#form-id, .form-class").cartAddForm({});
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
;(function(defaults, $, window, document, undefined) {
	'use strict';
	$.fn.extend({
		options : {
			// ajax options
			responseType : 'json',
			// events
			beforeSend : null,
			success : null,
			error : null
		},
		cartAddForm : function(options) {
			options = $.extend(this.options, options);	
			return $(this).each(function() {
				self = $(this);
				self.options = options;				
				
				self.on('submit', function(e){
					return self.submit(e);
				});
			});
		},
		submit : function(e){
			self = $(this);
			$.ajax({
				type: self.attr('method'),
				url:  self.attr('action'),
				data: self.serialize(),
				dataType : self.options.responseType,
				beforeSend: function(){
					if(typeof self.options.beforeSend == 'function'){
						self.options.beforeSend.call(self);
					}
				},
				success: function(response) {
					if(typeof self.options.success == 'function'){
						self.options.success.call(self, response);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if(typeof self.options.error == 'function'){
						self.options.error.call(self, jqXHR, textStatus, errorThrown);
					}
				},
				complete : function(jqXHR, textStatus){
					
					if(typeof self.options.error == 'function'){
						self.options.error.call(self, jqXHR, textStatus);
					}	
				}
			});
		}
	});
})({}, jQuery, window, document);
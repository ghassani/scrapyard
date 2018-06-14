function AjaxLogger(options) {
	this.debug 	 = false; /* SET TO FALSE WHEN PRODUCTION */
	this.options = $.extend({
		type : 'POST',
		dataType: 'JSON',
	}, options);
	
	this.events  = {
		initialize: function(){
			this.log_console('Initialize Event Called');
			
   			if(this.options.initialize){
   				this.options.initialize.call(this);
   				return;
   			}
		},
		beforeSend: function(){
			this.log_console('Before Send Event Called');
			
   			if(this.options.beforeSend){
   				this.options.beforeSend.call(this);
   				return;
   			}
		},
		error: function(){
			this.log_console('Error Event Called');
			
   			if(this.options.error){
   				this.options.error.call(this);
   				return;
   			}
   			
		},
		complete: function(){
			this.log_console('Complete Event Called');
			
   			if(this.options.complete){
  				this.options.complete.call(this);
  				return;
   			}
   			
		}
	};
	
	this.initialize();
};
	 
AjaxLogger.prototype.initialize = function() {
	if(!this.options.uri){
		this.log_console('Must Set URI Option');
		return false;
	}
	this.events.initialize.call(this);
};

AjaxLogger.prototype.log_console = function(message) {
	if(this.debug){
		console.log(message);
	}
};

AjaxLogger.prototype.log = function(message, data, uri) {
	if(this.debug){
		this.log_console(message);
	}
	
	var _data = { 
		'message' : message,
		details : {
			page: window.location.href, 
			referer: document.referrer,
			useragent: navigator.userAgent !=  undefined ? navigator.userAgent : 'N/A'
		}
	};
	
	if(data != undefined){
		$.extend(_data, data);
	}
	

	$this = this;
	
	$.ajax({
		type: this.options.type,
		url: uri != undefined ? uri : this.options.uri,
		data: _data,
		dataType : this.options.dataType,
		beforeSend: function(){
			$this.events.beforeSend.call($this);
		},
		success: function(response) {
			$this.events.complete.call($this);
		},
		error: function() {
			$this.events.error.call($this);
		}
	});
};
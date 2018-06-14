function AjaxResponseHandler(request, response, options) {
    this.request 	= request;
	this.response 	= response;
	this.options	= options;
	this.debug 		= false; /* SET TO FALSE WHEN PRODUCTION */
	
	this.events = {
		initialize: function(){
			this.log('Initialize Event Called');
			
   			if(this.options.initialize){
   				this.options.initialize.call(this);
   			}
		},
		modalShown: function(){
			this.log('Modal Shown Event Called');
			
   			if(this.options.modalShown){
   				this.options.modalShown.call(this);
   			}
		},
		modalClosed: function(){
			this.log('Modal Closed Event Called');
			
   			if(this.options.modalClosed){
   				this.options.modalClosed.call(this);
   			}
		},
		update: function(){
			this.log('Update Event Called');
			
			if(this.options.update){
   				this.options.update.call(this);
   			}
		},
		complete: function(){
			this.log('Complete Event Called');
			
   			if(this.options.complete){
  				this.options.complete.call(this);
   			}
   			if(this.response.redirect){
   				window.location = unescape(this.response.redirect);
   			}
		},
	};
	
	this.initialize();
};
	 
AjaxResponseHandler.prototype.initialize = function() {
	this.events.initialize.call(this);
	
	if(this.response.success == true){
		if(this.response.modal){
			this.modal = $(unescape(this.response.modal)).modal();
			var $this = this;
			
			$this.modal.on('shown.bs.modal', function () {
				$this.events.modalShown.call($this);
			}).modal().on('hide.bs.modal', function () {
				$this.events.modalClosed.call($this);
				$this.processDomUpdate();
				$this.events.complete.call($this);
			});
			return;
		}
	
		// no modal
		if(this.response.message){
			alert(this.response.message);
		}
		
		this.processDomUpdate();
		this.events.complete.call(this);
	} else {
		// not successfull
		if(this.response.modal){
			if(this.response.modal){
				this.modal = $(unescape(this.response.modal)).modal();
				var $this = this;
				
				$this.modal.on('shown', function () {
					$this.events.modalShown.call($this);
				}).modal().on('hide', function () {
					$this.events.modalClosed.call($this);
					//$this.processDomUpdate();
				});
				return;
			}
		} else if(this.response.message) {
			alert(this.response.message);
		}
	}

};

AjaxResponseHandler.prototype.log = function(message) {
	if(this.debug){
		console.log(message);
	}
};

AjaxResponseHandler.prototype.processDomUpdate = function() {
		
	if(this.response.replace_many){
		var $this = this;
		$.each(this.response.replace_many, function(target,html) {
			$(unescape(target)).empty().append($(unescape(html)));	
			$this.log("Updated Element(s): " +unescape(target));
		});	
	} else if(this.response.target && this.response.html && this.response.replace){
		if(this.options.replace){ // user defined onInsert function overrides default functionality
			this.options.replace.call(this);
		} else {
			var $target = '#'+unescape(this.response.target);
			this.log("Default Replace Into Element: " + $target );
			
			if(this.response.target && this.response.replace){
				$($target).empty().append($(unescape(this.response.html)));
				this.log("Updated Element(s): " +$target);
			} else if(this.response.target) {
				$($target).append($(unescape(this.response.html)));
				this.log("Replaced Element(s): " +$target);
			}		
		}
	} else 	if(this.response.target && this.response.html){
		
		if(this.options.insert){ // user defined onInsert function overrides default functionality
			this.options.insert.call(this);
		} else {
			var $target = '#'+unescape(this.response.target);
			this.log("Default Replace Into Element: " + $target );
			
			$($target).append($(unescape(this.response.html)));
			this.events.update.call(this);
		}
		
	}
	
	if(this.response.remove){
		if(this.response.remove_target){
			$(this.response.remove_target).fadeOut('slow', function(){
				$(this).remove();
				this.events.update.call(this);
			});
		} else if(this.response.link){
			this.response.link.fadeOut('slow', function(){
				$(this).remove();
				this.events.update.call(this);
			});
		}
	} 
};
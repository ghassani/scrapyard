function Confirmation(title, body, callback) {
    this.title 	= title;
	this.body 	= body;
	this.callback = callback;
	this.debug = false;

	this.initialize();
};
	 
Confirmation.prototype.initialize = function() {
	
};

Confirmation.prototype.log = function(message) {
	if(this.debug){
		console.log(message);
	}
};

Confirmation.prototype.confirm = function() {
	var html = $(this.buildModalHtml(this.title, this.body)).modal({
		
	});

	$('body').append(html);
	html.modal('show');
}

Confirmation.prototype.buildModalHtml = function(title, body) {
	var html = '<div id="modal-message" class="modal hide fade" tabindex="-1">' +
	    '<div class="modal-header">' +
	    	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
	    	'<h3>'+title+'</h3>' +
	    '</div>' +
	    '<div class="modal-body">' +
	    	body +
	    '</div>' +
	    '<div class="modal-footer">' +
			'<button type="button" data-dismiss="modal" class="btn">Ok</button>' +
	    '</div>' +
    '</div>';
}
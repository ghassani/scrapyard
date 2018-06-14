$(document).ready(function(){
	/*$('header:first-child').affix({
		offset: {
			top: 0,
		}
	});
	
	$('.side-nav').affix({
		offset: {
			top: function () {
		        return (this.top = $('header:first-child').outerHeight(true))
		    },
		    left: 0,
		}
	});*/

	$('textarea.wysiwyg').summernote();
    $('.datepicker').datepicker();
    
	/*each(function(i, e){
		$textarea = $(e);
		$editor = $('<div class="summernote-'+i+'">'+$textarea.val()+'</div>');
		
		$editor.insertAfter($textarea);

		$editor.summernote({
			onblur: function(e) {
				$textarea.val($(this).code());
			}
		});
		
	});*/
	
	
});
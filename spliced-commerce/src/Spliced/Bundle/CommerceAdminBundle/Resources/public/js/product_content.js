$(document).ready(function(){


	/**
	 * Add a Stylesheet by Prototype
	 */
	$('a.add-content-stylesheet').click(function(e){
		e.preventDefault();
		var $target = $("table.product-content-stylesheets");
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.find('tr.stylesheet').length));

		$prototype.find('a.delete-product-content-stylesheet').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
				});
			}		
		}); 
		
		$target.append($prototype);
	});
	
	/**
	 * Delete a Stylesheet
	 */
	$('a.delete-product-content-stylesheet').click(function(e){
		e.preventDefault();
		$target = $($(this).attr('data-target'));

		if(confirm("Are you sure?")){
			$target.fadeOut('fast', function(){
				$target.remove();
			});
		}			
	});
	
	/**
	 * Add a Javascript by Prototype
	 */
	$('a.add-content-javascript').click(function(e){
		e.preventDefault();
		var $target = $(".product-content-javascripts");
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.find('.javascript').length));

		$prototype.find('a.delete-product-content-javascript').click(function(e){
			e.preventDefault();

			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
				});
			}		
		}); 
		
		$target.append($prototype);
	});	
	
	/**
	 * Delete a Javascript
	 */
	$('a.delete-product-content-javascript').click(function(e){
		e.preventDefault();
		$target = $($(this).attr('data-target'));

		if(confirm("Are you sure?")){
			$target.fadeOut('fast', function(){
				$target.remove();
			});
		}			
	});
		
});
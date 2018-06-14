$(document).ready(function(){
	
	/**
	 * Formats the inputted name and formats it
	 */
	$('input.attribute-name-autoformat').change(function(e){
		$(this).val($(this).val().toLowerCase().replace(/\s/g, '_'));
	});
	
	/**
	 * Checks to see if the attribute already exists
	 */
	$('input.new-product-attribute-check').change(function(e){
		e.preventDefault();
		
		var input = $(this);
		
		if(!input.val()){
			return;
		} 

		// format in case it did not yet
		$('input.attribute-name-autoformat').change();
		
		$.ajax({
			type: "POST",
			url: XHR_BASE_URL + '/product-attribute-option/check-name',
			dataType : 'json',
			data: { name : $(this).val() },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == false){
					alert('Attribute Already Exists With ID '+response.id);
					input.val('');
				}
			},
			error: function() {
				$.unblockUI();
				alert('An Error Occoured');
			},
		});
	});
	
	/**
	 * Handles deletion of an existing attribute option 
	 */
	$('a.delete-product-attribute-option').click(function(e){
		e.preventDefault();
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		var $target = $($(this).attr('data-target'));

		$.ajax({
			type: "POST",
			url: $(this).attr('href'),
			dataType : 'json',
			data: { },
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				$.unblockUI();
				if(response.success == true){
					$target.fadeOut('fast', function(){
						$(this).remove();
					});
				}
			},
			error: function() {
				$.unblockUI();
				alert('An Error Occoured');
			},
		});
	});
	
	
	/**
	 * Add a Attribute Value From Prototype
	 */
	$('a.add-product-attribute-option-value').click(function(e){
		e.preventDefault();
		var $target = $('table.product-attribute-option-value-table tbody.main-body');
		var $noResultsHolder = $target.find('tr.no-results');
		var $currentCount = $target.find('tr.section-start').length;
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $currentCount));
		

		$prototype.find('a.delete-product-attribute-option').click(function(e){
			e.preventDefault();
			
			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
				});
			}
		});
		
		if($noResultsHolder.is(":visible")){
			$noResultsHolder.fadeOut('fast', function(){
				$target.append($prototype);
			});
		} else {			
			$target.append($prototype);
		}
	});
	
	
	/**
	 * Fired when an attribute option type is changed to disable
	 * fields which are not applicable to its type
	 */
	$("#commerce_product_attribute_option_optionType").change(function(e){
		if(!$(this).val()){
			return;
		}
		if($(this).val() == 3 || $(this).val() == 4){
			$("#commerce_product_attribute_option_onView").val(0).attr('disabled', 'disabled');
			$("#commerce_product_attribute_option_onList").val(0).attr('disabled', 'disabled');
			$("#commerce_product_attribute_option_filterable").val(0).attr('disabled', 'disabled');
			$("#commerce_product_attribute_option_position").val(0).attr('disabled', 'disabled');
			
		} else {
			$("#commerce_product_attribute_option_onView").val("").removeAttr('disabled');
			$("#commerce_product_attribute_option_onList").val("").removeAttr('disabled');
			$("#commerce_product_attribute_option_filterable").val("").removeAttr('disabled');
			$("#commerce_product_attribute_option_position").val(0).removeAttr('disabled');
		}
	});
	
	if($("#commerce_product_attribute_option_optionType").length){
		$("#commerce_product_attribute_option_optionType").change();
	}
	
	/**
	 * Handles Prototype when adding a validator to 
	 * attribute option of user selectable or user 
	 * input type 
	 */
	$("a.add-product-attribute-option-user-data-validator").click(function(e){
		e.preventDefault();
		var $target = $(this).closest('table').find('tbody');
		var $noResultsHolder = $target.find('tr.no-results');
		
		var $prototype = $($(this).attr('data-prototype').replace(/__name__/g, $target.children().length));

		$prototype.find('.attribute-data-validation-type').change(function(e){
			if($(this).val() == 8){ //is a regular expression, show additional options
				$prototype.find('.type-regexp-settings').removeClass('hide');
			} else {
				$prototype.find('.type-regexp-settings').addClass('hide');
			}
		});
		
		$prototype.find('a.delete-product-attribute-option-user-data-validator').click(function(e){
			e.preventDefault();
			
			if(confirm("Are you sure?")){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
					if($target.children().length == 1){
						$noResultsHolder.fadeIn('fast');
					}
				});
			}
		});

		if($noResultsHolder.is(":visible")){
			$noResultsHolder.fadeOut('fast', function(){
				$target.append($prototype);
			});
		} else {
			$target.append($prototype);
		}
	});
	
	/**
	 * Handles Deletion of Attribute Option Validators 
	 * for attributes of user input type
	 */
	$('a.delete-product-attribute-option-user-data-validator').click(function(e){
		e.preventDefault();
			
		if(confirm("Are you sure?")){
			$target = $(this).closest('table').find('tbody');
			$noResultsHolder = $target.find('tr.no-results');
			
			$(this).closest('tr').fadeOut('fast', function(){
				$(this).remove();
				if($target.children().length == 1){
					$noResultsHolder.removeClass('hide').fadeIn('fast', function(){

					});
				}
			});
		}
	});
	
	/**
	 * Handles Prototype when adding a product relation 
	 * to an attribute option value of user selectable type 
	 */
	$(".add-product-attribute-option-value-product-add").live('click', function(e){
		e.preventDefault();

		var $target = $(this).closest('table').find('tbody');
		var $noResultsHolder = $target.find('tr.no-results');
		var $currentCount = $noResultsHolder.length ? $target.children().length - 1 : $target.children().length;
		
		var $prototype = $($(this).attr('data-prototype').replace(/__product_name__/g, $currentCount));
		
		// initialize a typeahead for the newly created product row
		$prototype.find('input.product-attribute-option-value-product-typeahead').typeahead({
			name: 'products',
			remote: XHR_BASE_URL + '/product/ajax-search?q=%QUERY',
			engine: TypeaheadTemplateEngine,
			template: '<div class="typeahead-result-row">{{name}} - <i>{{sku}}</i></div>'
		});
		
		$prototype.find('a.delete-product-attribute-option-value-product').click(function(e){
			e.preventDefault();
			if(confirm('Are you sure?')){
				$prototype.fadeOut('fast', function(){
					$prototype.remove();
				});
			}
		});
		
		if($noResultsHolder.is(":visible")){
			$noResultsHolder.fadeOut('fast', function(){
				$target.append($prototype);
			});
		} else {
			$target.append($prototype);
		}
	});
	
	/**
	 * Handles Deletion of Existing (saved) Product Attribute Option Value
	 * Product Relations 
	 */
	$('a.delete-product-attribute-option-value-product').click(function(e){
		e.preventDefault();
		if(confirm('Are you sure?')){
			$($(this).attr('data-target')).fadeOut('fast', function(){
				$(this).remove();
			});
		}
	});
	
	
	/**
	 *Initialize TypeAhead for existing products related to
	 * the values of the currently viewed attribute 
	 */
	$('input.product-attribute-option-value-product-typeahead').typeahead({
		name: 'products',
		remote: XHR_BASE_URL + '/product/ajax-search?q=%QUERY',
		engine: TypeaheadTemplateEngine,
		template: '<div class="typeahead-result-row">{{name}} - <i>{{sku}}</i></div>'
	});
});

$(document).ready(function(){

	$("form.add-to-cart-form").ajaxForm({
		responseType : 'json',
		success : function(form, response, textStatus, jqXHR){			
			
			if(!response.success && !response.content && response.message){
				alert(response.message);
				return;
			}
			
			if(response.content){
				$content = $(unescape(response.content));
				
				if($content.find('.modal')){
					$modal = $content.modal();				
				
					$modal.on('hidden.bs.modal', function(){
						$('.modal-backdrop').remove();
					});
				} else if(response.message) {
					alert(response.message);
				}
			}
		},
		error : function(form, jqXHR, textStatus, errorThrown){
			if(typeof logger == 'function'){
				logger.log('Error Adding To cart',{
					'details':{ 
						'textStatus': textStatus, 
						'errorThrown' : errorThrown,						
						'responseText' : jqXHR.responseText,
					}
				});
			}
		}
	});
	
	/**
	 * Add Item to Cart
	
	$("form.add-to-cart-form").live('submit', function(e){
		e.preventDefault();
		var productId = $(this).find('input.product_id').val();
		var quantity = $(this).find('input.quantity').val();
		
		$.ajax({
			type: "POST",
			url: XHR_BASE_URL+"/cart/add",
			data: { "id": productId, "quantity": quantity },
			dataType : 'json',
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				//logger.log('Product '+productId +' Added to Cart', {}, XHR_BASE_URL+'/logger/log/javascript-notice');
				if(response.modal){
					$modal = $(unescape(response.modal)).modal();
	
					$modal.on('shown.bs.modal', function () {
						$(".modal-footer a").click(function(e){
							$modal.modal('toggle');
						});
					});
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				logger.log('Error Adding To cart',{
					'details':{ 
						'textStatus': textStatus, 
						'errorThrown' : errorThrown,						
						'responseText' : jqXHR.responseText,
					}
				});
				alert('Error Adding Product To Cart');
			}
		});
		
	}); */
	
	/**
	 * Remove Item from Cart
	 */
	$("a.cart-remove-item").live('click', function(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: $(this).attr('href'),
			data: { },
			dataType : 'json',
			beforeSend: function(){
				$.blockUI({ message: blockUiMessage });
			},
			success: function(response) {
				var responseHandler = new AjaxResponseHandler(this,response,{});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				logger.log('Error Deleting To cart',{
					'details':{ 
						'textStatus': textStatus, 
						'errorThrown' : errorThrown,						
						'responseText' : jqXHR.responseText,
					}
				});
				alert('Error Deleting Item From Cart');
			}
		});
	});
	
	/**
	 * 
	 */
	$("input.add-to-cart-qty").keypress(function(e) {
		if(e.which == 13){
			e.preventDefault();
			$("input.add-to-cart-qty").change();
		}
	});
	
	/**
	 * 
	 */
	$("input.add-to-cart-qty").change(function(e){
		e.preventDefault();
		var tierData = $.parseJSON(unescape($(this).attr('data-tier')));
		var priceUpdateTarget = $("#"+$(this).attr('data-target'));
		var quantity = $(this).val();
		var basePrice = $(this).attr('data-baseprice');
		
		if(!tierData.length){
			return;
		}
		
		for(i = 0; i < tierData.length; i++) {
			if(tierData[i].maxQuantity == -1){
				tierData[i].maxQuantity = 100000;
			}
			if(tierData[i].minQuantity <= quantity && quantity <= tierData[i].maxQuantity){
				switch (tierData[i].adjustmentType) {
	                case 'SUBTRACT_PERCENTAGE_PER_ITEM':
	                    var percentage = tierData[i].adjustment/100;
	                    var difference = basePrice * percentage;
	                    var newPrice = basePrice - difference;
	                    break;
	                case 'ADD_PERCENTAGE_PER_ITEM':
	                    var percentage = tierData[i].adjustment / 100;
	                    var difference = basePrice * percentage;
	                    var newPrice = basePrice + difference;
	                    break;
	                case 'FIXED_PER_ITEM':
	                    var newPrice = tierData[i].adjustment;
	                    break;
	                default:
	                	var newPrice = basePrice;
	                    break;
            	}
				
				if(tierData[i].options.round_to_next_dollar == true){
					newPrice = Math.ceil(newPrice) - .01;
				}
				
				if(priceUpdateTarget.length){
					priceUpdateTarget.html(newPrice);
				}
				return;
			}
		}
		
		if(priceUpdateTarget.length){
			/*totalPrice = basePrice*quantity;
			if(totalPrice != basePrice){
				priceUpdateTarget.html(totalPrice+" <small>$"+basePrice+" each</small>");
			} else {
				priceUpdateTarget.html(totalPrice);
			}*/
			priceUpdateTarget.html(basePrice);
		}
	});

});
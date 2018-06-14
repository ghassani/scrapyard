/**
 * Checkout Javascript Events and Functions
 */
$(document).ready(function(){

    /**
     *

     $('.set-shipping-to-billing').live('click', function(e){
		e.preventDefault();
		
		$("#checkout_shippingAttn").val(null);
		$("#checkout_shippingName").val($("#checkout_billingFirstName").val() + ' ' + $("#checkout_billingLastName").val());
		$("#checkout_shippingAddress").val($("#checkout_billingAddress").val());
		$("#checkout_shippingAddress2").val($("#checkout_billingAddress2").val());
		$("#checkout_shippingState").val($("#checkout_billingState").val());
		$("#checkout_shippingCity").val($("#checkout_billingCity").val());
		$("#checkout_shippingZipcode").val($("#checkout_billingZipcode").val());
		$("#checkout_shippingCountry").val($("#checkout_billingCountry").val());
		$("#checkout_shippingPhoneNumber").val($("#checkout_billingPhoneNumber").val());
		if($("#checkout_saveShippingAddress").attr('checked')){
			$("#checkout_saveShippingAddress").removeAttr('checked');
		}
	}); */

    /**
     *

     $('.checkout-payment-methods input[type=radio]').live('change', function(e){
		$method = $(this).val();
		$('.checkout-payment-methods').find('.payment_method_content[id!=method_'+$method+'_content]').fadeOut('fast', function(){
			$("#method_"+$method+"_content").removeClass('hidden');
			$("#method_"+$method+"_content").fadeIn('fast',function(){
				
			});
		});
	});	 */

    /**
     *

     $('.checkout-payment-methods .payment_method_selector a').live('click', function(e){
		e.preventDefault();
		$method = $(this).attr('data-method');
		if(!$method){
			return;
		}
		$('.checkout-payment-methods input[value='+$method+']').click();
		$('.checkout-payment-methods').find('.payment_method_content[id!=method_'+$method+'_content]').fadeOut('fast', function(){
			$("#method_"+$method+"_content").removeClass('hidden');
			$("#method_"+$method+"_content").fadeIn('fast',function(){
				
			});
		});
	}); */

    /**
     * On Credit Card Edit

     $(".checkout-edit-credit-card").live('click', function (e) {
		e.preventDefault();
		$existing = $("#existing-credit-card-overview");
		$target = $($(this).attr('data-target') != undefined ? $(this).attr('data-target') : '#existing-credit-card-overview');
		
		$existing.fadeOut('fast', function(){
			$target.fadeIn('fast', function(){
				$target.removeClass('hidden');
				$("#checkout_payment_creditCard_cardNumber").attr('data-original',$("#checkout_payment_creditCard_cardNumber").val()).val('');
				$("#checkout_payment_creditCard_cardExpiration").attr('data-original',$("#checkout_payment_creditCard_cardExpiration").val()).val('');
				$("#checkout_payment_creditCard_cardCvv").attr('data-original',$("#checkout_payment_creditCard_cardCvv").val()).val('');
			});
		});
	}); */


        // Change Shipping Country
    $('#checkout_shippingCountry').live('blur', function(e){
        // check for updated country
    });

    /**
     *
     */
    $('form.checkout-form11').live('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: XHR_BASE_URL+"/checkout",
            data: $(this).serialize(),
            dataType : 'json',
            async: false,
            cache: false,
            beforeSend: function(){
                $.blockUI({ message: blockUiMessage });
                $("#checkout_payment_creditCard_cardNumber").val('');
                $(".checkout-disable-on-submit").attr("disabled", "disabled");
            },
            success: function(response, textStatus, jqXHR) {
                $.unblockUI({ message: blockUiMessage });
                var responseHandler = new AjaxResponseHandler(this,response,{
                    modalShown : function(){
                        $(".checkout-disable-on-submit").removeAttr("disabled");
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown ) {
                $.unblockUI({ message: blockUiMessage });

                $(".checkout-disable-on-submit").removeAttr("disabled");

                logger.log('There was an error During Checkout Step Progression',{
                    'details':{
                        'textStatus': textStatus,
                        'errorThrown' : errorThrown,
                        'responseText' : jqXHR.responseText,
                    }
                });
            }
        });
    });

    /**
     *
     */
    $("a.checkout-move-step, button.checkout-move-step").live('click',function(e){
        e.preventDefault();
        var $step = $(this).attr('data-step');

        $.ajax({
            type: "GET",
            url: XHR_BASE_URL+"/checkout",
            data: { 'step' : $step },
            dataType : 'json',
            async: false,
            cache: false,
            beforeSend: function(){
                $.blockUI({ message: blockUiMessage });
            },
            success: function(response) {
                var responseHandler = new AjaxResponseHandler(this,response,{
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                window.location.href = XHR_BASE_URL + "/checkout?step="+$step;
            }
        });
    });



    $("a.saved-shipping-address").live('click', function(e){
        e.preventDefault();
        var $id = $(this).attr('data-id');
        var $target = $(this).attr('data-type');
        var $selector = $(this);

        $.ajax({
            type: "GET",
            url: XHR_BASE_URL+"/account/get-address/"+$id,
            dataType : 'json',
            beforeSend: function(){
                $.blockUI({ message: blockUiMessage });
            },
            success: function(response) {
                if(response.success){
                    if($target == 'shipping'){
                        $("#checkout_shippingAttn").val(unescape(response.attn));
                        $("#checkout_shippingName").val(unescape(response.first_name + ' ' + response.last_name));
                        $("#checkout_shippingAddress").val(unescape(response.address));
                        $("#checkout_shippingAddress2").val(unescape(response.address2));
                        $("#checkout_shippingState").val(unescape(response.state));
                        $("#checkout_shippingCity").val(unescape(response.city));
                        $("#checkout_shippingZipcode").val(unescape(response.zipcode));
                        $("#checkout_shippingCountry").val(unescape(response.country));
                        $("#checkout_shippingPhoneNumber").val(unescape(response.phoneNumber));
                        if($("#checkout_saveShippingAddress").attr('checked')){
                            $("#checkout_saveShippingAddress").removeAttr('checked');
                        }
                    } else {
                        $("#checkout_billingFirstName").val(unescape(response.first_name));
                        $("#checkout_billingLastName").val(unescape(response.last_name));
                        $("#checkout_billingAddress").val(unescape(response.address));
                        $("#checkout_billingAddress2").val(unescape(response.address2));
                        $("#checkout_billingState").val(unescape(response.state));
                        $("#checkout_billingCity").val(unescape(response.city));
                        $("#checkout_billingZipcode").val(unescape(response.zipcode));
                        $("#checkout_billingCountry").val(unescape(response.country));
                        $("#checkout_billingPhoneNumber").val(unescape(response.phoneNumber));
                        if($("#checkout_saveBillingAddress").attr('checked')){
                            $("#checkout_saveBillingAddress").removeAttr('checked');
                        }
                    }

                    $selector.val('');
                }
            },
            error: function(jqXHR, textStatus, errorThrown ) {
                logger.log('An error occoured pulling customer stored address information',{
                    'details':{
                        'textStatus':textStatus,
                        'errorThrown' : errorThrown,
                    }
                });
                alert('An error occoured pulling address information');
            }
        });
    });


    $('form.checkout-form input, form.checkout-form select, form.checkout-form textarea').change(function(e){

    });
});
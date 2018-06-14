/**
 * CheckoutForm
 *
 * Checkout form for Default Checkout
 */
function CheckoutForm(form, options) {
    self = this;

    this.options = {
        debug : false,
    };

    if(typeof options != 'undefined'){
        $.extend(this.options, options);
    }

    this.form = form instanceof jQuery ? form : $(form);

    if(!this.form.length){
        this.log('Form Not Found');
        return;
    }

    if(typeof $ == 'undefined'){
        if(typeof jQuery != 'undefined'){
            $ = jQuery;
        } else {
            this.log('jQuery is Required');
            return;
        }
    }

    this.initialize();
};

/**
 *
 */
CheckoutForm.prototype.initialize = function() {
    self = this;

    this.form.on('submit', this.submit);
};

/**
 *
 */
CheckoutForm.prototype.submit = function(e) {
    var isValid = true;
    self.form.find('input, select, textarea').each(function(i, input){
        $input = $(input);
        if($input.hasAttr('required') && $input.attr('required') == 'required'){
            $input.parent('form-group').addClass('has-error');
            isValid = false;
        }
    });

    return isValid;
};

/**
 *
 */
CheckoutForm.prototype.log = function(message) {
    if(this.options.debug){
        console.log(message);
    }
};

/**
 *
 */
$(document).ready(function($){
    if($('#form-checkout').length){
        checkoutForm = new CheckoutForm($('#form-checkout'), {
            debug : true
        });
    }
});

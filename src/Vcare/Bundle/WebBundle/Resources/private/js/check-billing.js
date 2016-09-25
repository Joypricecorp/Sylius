$('#vcare_checkout_addressing_differentBillingAddress').change(function(){
    if(this.checked)
        $('#checkout-billing-address-container').fadeIn('slow');
    else
        $('#checkout-billing-address-container').fadeOut('slow');
});

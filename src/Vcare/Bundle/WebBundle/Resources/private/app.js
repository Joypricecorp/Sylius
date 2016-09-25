(function($) {
    console.log('web app start ..');

    $('[data-toggle="tooltip"]').tooltip()

    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });

    // cart summary
    $('#form-cart-summary')
        .areYouSure({'silent':true})
        .on('dirty.areYouSure', function() {
            $(this).find('.cart-dirty-no').hide()
            $(this).find('.cart-dirty-yes').show()
        })
        .on('clean.areYouSure', function() {
            $(this).find('.cart-dirty-no').show()
            $(this).find('.cart-dirty-yes').hide()
        })
    ;

})(jQuery);

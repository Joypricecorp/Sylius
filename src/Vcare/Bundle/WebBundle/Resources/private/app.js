(function($) {
    console.log('web app start ..');

    $('[data-toggle="tooltip"]').tooltip();

    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active');
    });

    // cart summary
    $('#form-cart-summary')
        .areYouSure({'silent':true})
        .on('dirty.areYouSure', function() {
            $('.cart-dirty-no').hide();
            $('.cart-dirty-yes').show();
        })
        .on('clean.areYouSure', function() {
            $('.cart-dirty-no').show();
            $('.cart-dirty-yes').hide();
        })
    ;

    $(document).on('submit', 'form', function () {
        $('.btn').attr('disabled', true);
    });

    $(document).on('change', '.paysbuy-method-choices input', function () {
        $(this)
            .closest('.list-group-item')
            .find('.payment-method input')
            .prop('checked', true)
        ;
    });
    $('.navbar [data-toggle="dropdown"]').bootstrapDropdownHover({
        // see next for specifications
    });
})(jQuery);

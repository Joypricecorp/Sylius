(function($) {
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

    $(document).on('submit', 'form:not([data-special]):not([data-ajax-form])', function (e) {
        e.preventDefault();

        $(this).find('button, .btn').attr('disabled', true);

        this.submit();
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

    // product show
    $("img.lazy").lazyload({
        container: $(".product-show-content")
    });

    $('.product-show-content [data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $("img.lazy").lazyload({
            container: $(".product-show-content")
        });
    })
})(jQuery);

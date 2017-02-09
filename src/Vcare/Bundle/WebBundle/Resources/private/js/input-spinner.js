$(document).on('click', '.quantity-box--down', function () {
    var $input = $(this).closest('.quantity-box').find('input');
    var qty = parseInt($input.val()) - 1;
    $input.val(qty ? qty : 1);
    $input.trigger('change');
});

$(document).on('click', '.quantity-box--up', function () {
    var $input = $(this).closest('.quantity-box').find('input');
    $input.val(parseInt($input.val()) + 1);
    $input.trigger('change');
});

$(document).on('keyup', '.quantity-box input', function (e) {
    var val = parseInt($(this).val());

    if (1 > val || isNaN(val)) {
        $(this).val(1);
    }

    return true;
});

var quantityChanged = false;
$(document).on('change', 'form[name=sylius_cart] .quantity-box input', function (e) {
    if (quantityChanged) {
        return;
    }

    setTimeout(function() {
        $(e.target).closest('form').submit();
    }, 2000);

    quantityChanged = true;
});

$(function () {
    $(document).on('change', '.address-trigger', function(){
        if(this.checked)
            $('#checkout-billing-address-container').fadeIn('fast');
        else
            $('#checkout-billing-address-container').fadeOut('fast');
    });

    $(document).on('change', 'select[name$="[countryCode]"]', function(event) {
        var $select = $(event.currentTarget);
        var $provinceContainer = $select.closest('.address-container').find('.province-container');

        var provinceSelectFieldName = $select.attr('name').replace('country', 'province');
        var provinceInputFieldName = $select.attr('name').replace('countryCode', 'provinceName');

        if ('' === $select.val()) {
            $provinceContainer.fadeOut('fast', function () {
                $provinceContainer.html('');
            });

            return;
        }

        $.get($provinceContainer.attr('data-url'), {countryCode: $(this).val()}, function (response) {
            if (!response.content) {
                $provinceContainer.fadeOut('fast', function () {
                    $provinceContainer.html('');
                });
            } else if (-1 !== response.content.indexOf('select')) {
                $provinceContainer.fadeOut('fast', function () {
                    $provinceContainer.html(response.content.replace(
                        'name="sylius_address_province"',
                        'name="' + provinceSelectFieldName + '"'
                    ));

                    $provinceContainer.fadeIn();
                });
            } else {
                $provinceContainer.fadeOut('fast', function () {
                    $provinceContainer.html(response.content.replace(
                        'name="sylius_address_province"',
                        'name="' + provinceInputFieldName + '"'
                    ));

                    $provinceContainer.fadeIn();
                });
            }
        });
    });

    if('' === $.trim($('div.province-container').text())) {
        $('select[name$="[countryCode]"]').trigger('change');
    }
});

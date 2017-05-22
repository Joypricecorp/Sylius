/*
(function ( $ ) {
    'use strict';

    $.fn.extend({
        addToCart: function () {
            var element = $(this);
            var href = $(element).attr('action');
            var redirectUrl = $(element).data('redirect');
            var validationElement = $('#sylius-cart-validation-error');

            $(element).api({
                method: 'POST',
                on: 'submit',
                cache: false,
                url: href,
                beforeSend: function (settings) {
                    settings.data = $(this).serialize();

                    return settings;
                },
                success: function (response) {
                    validationElement.addClass('hidden');
                    window.location.replace(redirectUrl);
                },
                error: function (response) {
                    validationElement.removeClass('hidden');
                    var validationMessage = '';

                    $.each(response.errors.errors, function (key, message) {
                        validationMessage += message;
                    });
                    validationElement.html(validationMessage);
                    $(element).removeClass('loading');
                }
            });
        }
    });
})( jQuery );
*/

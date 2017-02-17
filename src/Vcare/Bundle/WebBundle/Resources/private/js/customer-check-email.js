$(function() {
    var validateEmail = function(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    };

    var $loginForm = $('.api-login-form');
    var loading = false;

    var $email = $('#sylius_checkout_address_customer_email').on('keyup keypress blur change', function (e) {
        var $el = $(this);

        if (!loading && !$loginForm.is(':visible') && validateEmail($el.val())) {
            loading = true;

            $.ajax({
                url: $el.data('url'),
                data: { email: $el.val() },
                error: function (res) {
                    loading = false;
                },
                success: function (res) {
                    $loginForm.show();
                    loading = false;
                }
            });
        }
    });

    $('#api-login-submit').click(function (e) {
        e.preventDefault();

        var $el = $(this).attr('disabled', true);
        var $err = $('#api-validation-error');

        $err.html('');

        $.ajax({
            url: $el.data('url'),
            type: 'POST',
            data: {
                _username: $email.val(),
                _password: $loginForm.find('[type=password]').val(),
                _csrf_shop_security_token: $loginForm.find('[name=_csrf_shop_security_token]').val()
            },
            error: function (res) {
                var msg = res.responseJSON.message;

                $el.attr('disabled', false);

                if (401 === res.status) {
                    msg = "Invalid credentials. / รหัสผ่านไม่ถุกต้อง";
                }

                $err.html('<div class="alert alert-danger m-t-1">' + msg + '</div>');
            },
            success: function (res) {
                $el.attr('disabled', false);
                window.location.reload();
            }
        });
    });
});

$(document).on('click', '[data-action-url]', function (e) {
    e.preventDefault();

    var $el = $(this);
    var redirectUrl = $el.data('redirect');
    var csrfToken = $el.data('csrf-token');

    $el.attr('disabled', true);

    $.ajax({
        type: 'DELETE',
        url: $el.data('action-url'),
        data: {
            _csrf_token: csrfToken,
            _method: 'DELETE'
        },
        success: function (response) {
            window.location.replace(redirectUrl);
        }
    });
});

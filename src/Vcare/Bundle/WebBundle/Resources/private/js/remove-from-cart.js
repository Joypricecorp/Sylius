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
            if (redirectUrl) {
                window.location.replace(redirectUrl);
                return;
            }

            $el.closest('tr').remove();
            window.location.reload();
        }
    });
});

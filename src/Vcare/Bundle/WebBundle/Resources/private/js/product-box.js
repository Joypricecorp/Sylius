$(".product-box").hover(function () {
        $(this).parent().find('.flickity-prev-next-button').show();
    },
    function () {
        $(this).parent().find('.flickity-prev-next-button').hide();
    }
);

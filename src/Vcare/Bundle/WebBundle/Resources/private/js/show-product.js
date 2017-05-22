var loaderDiv = '<div class="preload_wrapper"><div id="preloader_1"><span></span><span></span> <span></span><span></span><span></span></div></div>';
$(document).ready(function () {
    var current_image;
    $(document).on('click', '.product-show .carousel-item a', function (e) {
        current_image = $(this);
        e.preventDefault();

        var img = $(this).attr('href');

        if ($('.product-show-pictures__preview .preload_wrapper').length == 0) {
            $('.product-show-pictures__preview').prepend(loaderDiv);
        }

        var tmpImg = new Image();
        tmpImg.onload = imageLoaded;
        tmpImg.src = img;
    });

    var imageLoaded = function () {
        $('.product-show-pictures__preview').html(this);

        //for img-zoom
        var imgzoom = current_image.find('.xl-image-path').val();
        $(this).attr('class', 'zoom').attr('data-magnify-src', '' + imgzoom + '').css('display', 'block').magnify();
    }
});

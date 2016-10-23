$(document).ready(function() {

    $(window).scroll(function () {

        if ($(window).scrollTop() > 35) {
            $('.nav-main__menu').addClass('navbar-fixed');
        }
        if ($(window).scrollTop() < 35) {
            $('.nav-main__menu').removeClass('navbar-fixed');
        }
    });

    //nav-tiny for >sm's screen size
    $(".nav-main__menu-toggle button").click(function() {
        $(".category-toggle").slideToggle('fast', function() {
            $(this).toggleClass('category-toggle__on');
        });
    });
});

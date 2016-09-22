var gulp = require('gulp');
var chug = require('gulp-chug');

gulp.task('admin', function() {
    gulp.src('src/Sylius/Bundle/AdminBundle/Gulpfile.js', { read: false })
        .pipe(chug())
    ;
});

gulp.task('shop', function() {
    gulp.src('src/Sylius/Bundle/ShopBundle/Gulpfile.js', { read: false })
        .pipe(chug())
    ;
});

gulp.task('cms', function() {
    gulp.src('src/Toro/Bundle/CmsBundle/Gulpfile.js', { read: false })
        .pipe(chug())
    ;
});

var concat = require('gulp-concat');

gulp.task('cms-merge', function() {
    gulp.src(['web/assets/admin/js/app.js', 'web/assets/cms/js/app.js'])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('web/assets/admin/js/'))
    ;

    gulp.src(['web/assets/admin/css/style.css', 'web/assets/cms/css/style.css'])
        .pipe(concat('style.css'))
        .pipe(gulp.dest('web/assets/admin/css/'))
    ;
});

gulp.task('default', ['admin', 'shop', 'cms']);

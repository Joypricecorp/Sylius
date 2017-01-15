var gulp = require('gulp');
var chug = require('gulp-chug');
var argv = require('yargs').argv;

config = [
    '--rootPath',
    argv.rootPath || '../../../../web/assets/',
    '--nodeModulesPath',
    argv.nodeModulesPath || '../../../../node_modules/'
];

gulp.task('admin', function() {
    gulp.src('src/Sylius/Bundle/AdminBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config }))
    ;
});

gulp.task('admin-watch', function () {
    gulp.src('src/Sylius/Bundle/AdminBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config, tasks: 'watch' }))
    ;
});

gulp.task('shop', function() {
    gulp.src('src/Sylius/Bundle/ShopBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config }))
    ;
});

gulp.task('shop-watch', function () {
    gulp.src('src/Sylius/Bundle/ShopBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config, tasks: 'watch' }))
    ;
});

gulp.task('web', function() {
    gulp.src('src/Vcare/Bundle/WebBundle/Gulpfile.js', { read: false })
        .pipe(chug())
    ;
});

gulp.task('web-watch', function() {
    gulp.src('src/Vcare/Bundle/WebBundle/Gulpfile.js', { read: false })
        .pipe(chug({ tasks: 'web-watch' }))
    ;
});

gulp.task('cms', function() {
    gulp.src('./vendor/toro/cms-bundle/Gulpfile.js', { read: false })
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

gulp.task('web-merge', function() {
    gulp.src(['web/assets/web/js/app.js', 'web/bundles/vcareweb/js/app.js'])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('web/assets/web/js/'))
    ;

    gulp.src(['web/assets/web/css/style.css', 'web/bundles/vcareweb/css/style.css'])
        .pipe(concat('style.css'))
        .pipe(gulp.dest('web/assets/web/css/'))
    ;
});

gulp.task('default', ['admin', 'shop', 'cms', 'web']);

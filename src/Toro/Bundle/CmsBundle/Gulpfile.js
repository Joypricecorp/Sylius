var concat = require('gulp-concat');
var env = process.env.GULP_ENV;
var gulp = require('gulp');
var gulpif = require('gulp-if');
var livereload = require('gulp-livereload');
var merge = require('merge-stream');
var order = require('gulp-order');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');

var rootPath = '../../../../web/assets/';
var cmsRootPath = rootPath + 'cms/';
console.log(process.env.GULP_ENV);
var paths = {
    cms: {
        js: [
            '../../../../node_modules/codemirror/lib/codemirror.js',
            '../../../../node_modules/codemirror/mode/twig/twig.js',
            '../../../../node_modules/codemirror/mode/xml/xml.js',
            '../../../../node_modules/codemirror/mode/yaml/yaml.js',
            '../../../../node_modules/codemirror/mode/javascript/javascript.js',
            'Resources/private/js/**'
        ],
        sass: [
            'Resources/private/sass/**'
        ],
        img: [
            'Resources/private/img/**'
        ],
        css: [
            '../../../../node_modules/codemirror/lib/codemirror.css'
        ]
    }
};

gulp.task('cms-js', function () {
    return gulp.src(paths.cms.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(cmsRootPath + 'js/'))
    ;
});

gulp.task('cms-css', function() {
    var cssStream = gulp.src(paths.cms.css)
        .pipe(concat('css-files.css'))
    ;

    var sassStream = gulp.src(paths.cms.sass)
        .pipe(sass())
        .pipe(concat('sass-files.scss'))
    ;

    return merge(cssStream, sassStream)
        .pipe(order(['css-files.css', 'sass-files.scss']))
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(cmsRootPath + 'css/'))
        .pipe(livereload())
    ;
});

gulp.task('cms-img', function() {
    return gulp.src(paths.cms.img)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(cmsRootPath + 'img/'))
    ;
});

gulp.task('cms-watch', function() {
    livereload.listen();

    gulp.watch(paths.cms.js, ['cms-js']);
    gulp.watch(paths.cms.sass, ['cms-css']);
    gulp.watch(paths.cms.css, ['cms-css']);
    gulp.watch(paths.cms.img, ['cms-img']);
});

gulp.task('default', ['cms-js', 'cms-css', 'cms-img']);
gulp.task('watch', ['default', 'cms-watch']);

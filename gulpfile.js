var gulp = require( 'gulp'),
    postcss = require('gulp-postcss'),
    watch = require('gulp-watch'),
    autoprefixer = require('autoprefixer'),
    sass = require('gulp-sass'),
    plumber = require('gulp-plumber'),
    cleanCSS = require('gulp-clean-css'),
    sourcemaps = require('gulp-sourcemaps');

//For the V2 SASS 
var mainSass = 'src/compass_web/sass/main_v2.scss';
var pathSass = 'src/compass_web/sass/v2/**/*.scss';
var pathCss = 'src/public/w/css/';

gulp.task('sass', function () {
    gulp.src(mainSass)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceMap: true,
            errLogToConsole: true
        }))
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions'] }) ]))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(pathCss));
});

gulp.task('watch', function () {
    gulp.watch(pathSass, ['sass']);
});

gulp.task('default', [
    'sass'
]);
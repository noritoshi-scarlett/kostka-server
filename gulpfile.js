var gulp = require('gulp'),
    minifyCSS = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    babel = require('gulp-babel'),
    prefix = require('gulp-autoprefixer');

// Minifies JS
gulp.task('scripts', function(){
    return gulp.src('public/assets/javascript/src/*.js')
    .pipe(concat('site.min.js'))
    .pipe(babel({
        presets: ['@babel/env']
    }))
    .pipe(uglify())
    .pipe(gulp.dest('public/assets/javascript/'));
});

// Minifies CSS
gulp.task('styles', function(){
    return gulp.src('public/assets/stylesheet/site.css')
    .pipe(concat('site.min.css'))
    .pipe(minifyCSS())
    .pipe(prefix('last 2 versions'))
    .pipe(gulp.dest('public/assets/stylesheet'));
});

gulp.task('default', function() {
    gulp.run('styles');
    gulp.run('scripts');
    gulp.watch('public/assets/stylesheet/src/', function(){
        gulp.run('styles');
    });
    gulp.watch('public/assets/javascript/src/', function(){
        gulp.run('scripts');
    });
});
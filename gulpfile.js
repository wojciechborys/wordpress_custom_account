const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync').create();
const cleanCSS = require('gulp-clean-css');
const obfuscate = require('gulp-javascript-obfuscator');

function styles(){
	return gulp.src('./assets/src/scss/app.scss')
    .pipe(sass().on('error',sass.logError))
	.pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest('./assets/dist/css/'))
    .pipe(browserSync.stream());
}

function watch(){
	styles();
	scripts();
	browserSync.init({
		https: false,
		host: 'localhost',
		proxy: 'http://fundacja-pern.localhost/'
	});
    gulp.watch('./assets/src/scss/*.scss', styles);
	gulp.watch('./../**/*.php').on('change', browserSync.reload);
	gulp.watch('./assets/src/js/*.js').on('change', function(){
		scripts();
		browserSync.reload();
	});
}

function scripts(){
	return gulp.src('./assets/src/js/*.js')
	.pipe(obfuscate())
	.pipe(gulp.dest('./assets/dist/js/'));
}

function taskDefault(){
	styles();
	return scripts();
}

exports.styles = styles;
exports.watch = watch;
exports.default = taskDefault;
const gulp = require('gulp');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const { scr, series, parallel, dest, watch } = require('gulp');


function jsTask() {
  return gulp.src([
  'js/bootstrap4/bootstrap.min.js',
  'js/alertMsg.min.js',
  'js/jQuery-3.3.1.min.js',
  'js/jQuery-3.4.1.min.js',
  'js/starrr.js'],
            { allowEmpty: true },
            {base: 'js/'})
  .pipe(sourcemaps.init())
  .pipe(concat('script.js'))
  .pipe(uglify())
  .pipe(sourcemaps.write('.'))
  .pipe(dest('js'))
}

exports.jsTask = jsTask;

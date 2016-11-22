var gulp = require('gulp');
var uglify = require('gulp-uglifyjs');

gulp.task('default', function() {
    gulp.src('scripts/*start.js')
        .pipe(uglify({output:{beautify:false}}))
        .pipe(gulp.dest('./dist/js'))

});
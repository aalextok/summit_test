var gulp		=	require('gulp'),
	sass		=	require('gulp-sass'),
	minify		=	require('gulp-minify-css'),
	rename		=	require('gulp-rename');

gulp.task('sass', function(){
	gulp.src('sass/main.scss')
	.pipe(sass())
	// .pipe(minify()) // Enable for live environment
	.pipe(rename('admin.css'))
	.pipe(gulp.dest('web/css/'));
});


gulp.task('watch', function(){
	gulp.watch('sass/**/*.scss', ['sass']);
});

gulp.task('default',['sass', 'watch']);
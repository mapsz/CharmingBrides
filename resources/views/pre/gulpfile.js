var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var webpack  = require('webpack-stream');
var named = require('vinyl-named');
var livereload = require('gulp-livereload');
const babel = require('gulp-babel');
var less = require('gulp-less');
const autoprefixer = require('gulp-autoprefixer');
var uglifycss = require('gulp-uglifycss');
var plumber = require('gulp-plumber');
const htmlmin = require('gulp-htmlmin');
const fontello = require('gulp-fontello');



// Admin\public path
admin = false;
// admin = false;
if(admin){
	var src =  './src/admin';
	// var dest = 'adminAssets/'
	var dest = 'admin/'
}else{
	var src =  './src/public';
	var dest = '';
}


// JavaScript
gulp.task('js', function() {
	
	js(src + '/script', '../../../public/'+dest+'js');

	return true
});

// CSS
gulp.task('css', function () {

	css(src + '/style', '../../../public/'+dest+'css');

	return true;
});

// HTML
gulp.task('html', function () {

	html(src + '/html/**/*.blade.php', '../'+dest);

    return true;
});

// ICONS
gulp.task('icons', function () {

	icons(src, '../../../public/'+dest+'font');

	return true;

});



function css (src, dest){
	//Less
	gulp.src(src + '/*.less')
		.pipe(plumber())
		.pipe(less())
		.pipe(gulp.dest(src))

	//Css
	gulp.src(src + '/*.css')
		.pipe(plumber())		
		.pipe(autoprefixer())
		.pipe(uglifycss())
		.pipe(concat('style.css'))
		.pipe(gulp.dest(dest))
		.pipe(livereload());

	return true;
}

function html(src, dest){
  gulp.src(src)
    .pipe(plumber())
    // .pipe(htmlmin({ collapseWhitespace: true,
				// 	removeComments	:true
				// }))
    .pipe(gulp.dest(dest))
    .pipe(livereload());

    return true;
}

function js(src, dest){

	var webpackOptions = {
		// watch: true,
		// mode: 'production', // @@@
		mode: 'development', // @@@
			module: {
				rules: [{
					test: /\.css$/,
					use: ['style-loader', 'css-loader']
				}]
			}
	}

	gulp.src([src + '/*.js'])    
		.pipe(plumber())
		.pipe(named())
		.pipe(webpack(webpackOptions))
		.pipe(babel())
		.pipe(uglify())
		.pipe(gulp.dest(dest))
		.pipe(livereload());

	return true;
}

function icons(src, dest){

	//icons
	gulp.src(src + '/icons/config.json')
		.pipe(plumber())
		.pipe(fontello())
		.pipe(gulp.dest(src + '/icons'))

	//css
	// gulp.src(src + '/icons/css/*.css, ' + src + '/icons/css/!fontello-embedded.css')
	gulp.src(src + '/icons/css/*.css')
		.pipe(plumber())
		.pipe(gulp.dest(src + '/style'))

	// fonts
	gulp.src(src + '/icons/font/*')
		.pipe(gulp.dest(dest))

	return true;
}

//default 
gulp.task('default',['icons', 'html','js','css']);
//watch
gulp.task('watch', function() {
	livereload.listen();
	gulp.watch(src + '/script/*.js', ['js']);
	gulp.watch(src + '/style/*.less', ['css']);
	gulp.watch(src + '/html/**/*.blade.php', ['html']);
});

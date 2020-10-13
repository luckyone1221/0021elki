var gulp       	 = require('gulp'), // Подключаем Gulp
	sass         = require('gulp-sass'), //Подключаем Sass пакет,
	browserSync  = require('browser-sync'), // Подключаем Browser Sync
	concat       = require('gulp-concat'), // Подключаем gulp-concat (для конкатенации файлов)
	uglify       = require('gulp-uglifyjs'), // Подключаем gulp-uglifyjs (для сжатия JS)
	cssnano      = require('gulp-cssnano'), // Подключаем пакет для минификации CSS
	cleanCSS 	 		= require('gulp-clean-css'), //очистка от мусора
	rename       = require('gulp-rename'), // Подключаем библиотеку для переименования файлов // not working!!
	del          = require('del'), // Подключаем библиотеку для удаления файлов и папок
	imagemin     = require('gulp-imagemin'), // Подключаем библиотеку для работы с изображениями
	pngquant     = require('imagemin-pngquant'), // Подключаем библиотеку для работы с png
	cache        = require('gulp-cache'), // Подключаем библиотеку кеширования
	autoprefixer = require('gulp-autoprefixer');// Подключаем библиотеку для автоматического добавления префиксов

//custom js compile
var commonJsSrc = 'catalog/view/theme/elki/new-js/new-common.js'; //remake
var domain = 'http://elki.loc';
var babel = require('gulp-babel');

//
gulp.task('sass', function() { // Создаем таск Sass
	return gulp.src('catalog/view/theme/elki/stylesheet/*.sass') // Берем источник
		.pipe(sass()) // Преобразуем Sass в CSS посредством gulp-sass
		.pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], { cascade: true })) // Создаем префиксы
		.pipe(cleanCSS())// очистка от мусора
		.pipe(gulp.dest('catalog/view/theme/elki/stylesheet/')) // Выгружаем результата в папку app/css
		.pipe(browserSync.reload({stream: true})); // Обновляем CSS на странице при изменении
});

gulp.task('browser-sync', function() { // Создаем таск browser-sync
	browserSync({ // Выполняем browserSync
		proxy: `${domain}`,
		notify: false // Отключаем уведомления
	});
});
gulp.task('custom-js', function() {
	return gulp.src(commonJsSrc)
		.pipe(concat('new-common.min.js'))
		.pipe(babel())
		//.pipe(uglify())
		.pipe(gulp.dest('catalog/view/theme/elki/new-js/'))
		.pipe(browserSync.reload({stream: true}));
});

/*gulp.task('code', function() {
	return gulp.src('app/*.html')
		.pipe(browserSync.reload({ stream: true }))
});*/


gulp.task('watch', function() {
	gulp.watch('catalog/view/theme/elki/**/*.sass', gulp.parallel('sass')); // Наблюдение за sass файлами
	gulp.watch('catalog/view/theme/elki/new-js/new-common.js', gulp.parallel('custom-js')); // Наблюдение за главным JS файлом и за библиотеками
	//new
	//gulp.watch('catalog/view/theme/elki/template/**/*.twig', gulp.parallel('browser-sync')); // Наблюдение за twig файлами в теме

	//old
	//gulp.watch('app/*.html', gulp.parallel('code')); // Наблюдение за HTML файлами в корне проекта
	//gulp.watch(['app/js/common.js', 'app/libs/**/*.js'], gulp.parallel('scripts')); // Наблюдение за главным JS файлом и за библиотеками
});
gulp.task('default', gulp.parallel('sass', 'custom-js', 'watch', 'browser-sync'));
//gulp.task('build', gulp.parallel('prebuild', 'clean', 'img', 'sass', 'scripts'));



/*gulp.task('scripts', gulp.parallel('custom-js'), function() {
	return gulp.src([ // Берем все необходимые библиотеки
		'app/libs/jquery/dist/jquery.min.js', // Берем jQuery
		'app/libs/bxslider-4-4.2.12/dist/jquery.bxslider.min.js', // bx-slider
		'app/libs/fotorama-4.6.4/fotorama.js',//fotorama
		'app/libs/fontawesome-free-5.12.1-web/js/all.min.js', // fontAwesome
		'app/libs/selectize.js-0.12.4/dist/js/standalone/selectize.min.js', // selectize
		])
		.pipe(concat('libs.min.js')) // Собираем их в кучу в новом файле libs.min.js
		.pipe(uglify()) // Сжимаем JS файл
		.pipe(gulp.dest('app/js'))// Выгружаем в папку app/js
		.pipe(browserSync.reload({stream: true})); //page reload on change main js file
});*/


/*gulp.task('css-libs', function() {
	return gulp.src('app/sass/libs.sass') // Выбираем файл для минификации
		.pipe(sass()) // Преобразуем Sass в CSS посредством gulp-sass
		.pipe(cssnano()) // Сжимаем
		.pipe(rename({suffix: '.min'})) // Добавляем суффикс .min
		.pipe(gulp.dest('app/css')); // Выгружаем в папку app/css
});*/

/*gulp.task('clean', async function() {
	return del.sync('dist'); // Удаляем папку dist перед сборкой
});*/

// gulp.task('img', function() {
// 	return gulp.src('app/img/**/*') // Берем все изображения из app
// 		.pipe(cache(imagemin({ // С кешированием
// 		// .pipe(imagemin({ // Сжимаем изображения без кеширования
// 			interlaced: true,
// 			progressive: true,
// 			svgoPlugins: [{removeViewBox: false}],
// 			use: [pngquant()]
// 		}))/**/)
// 		.pipe(gulp.dest('dist/img')); // Выгружаем на продакшен
// });

// gulp.task('prebuild', async function() {
//
// 	var buildCss = gulp.src([ // Переносим библиотеки в продакшен
// 		'app/css/main.css',
// 		'app/css/libs.min.css'
// 		])
// 	.pipe(gulp.dest('dist/css'))
//
// 	var buildFonts = gulp.src('app/fonts/**/*') // Переносим шрифты в продакшен
// 	.pipe(gulp.dest('dist/fonts'))
//
// 	var buildJs = gulp.src('app/js/**/*') // Переносим скрипты в продакшен
// 	.pipe(gulp.dest('dist/js'))
//
// 	var buildHtml = gulp.src('app/*.html') // Переносим HTML в продакшен
// 	.pipe(gulp.dest('dist'));
//
// });

// gulp.task('clear', function (callback) {
// 	return cache.clearAll();
// })

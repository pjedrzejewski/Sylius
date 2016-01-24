gulp = require 'gulp'
gulpif = require 'gulp-if'
uglify = require 'gulp-uglify'
uglifycss = require 'gulp-uglifycss'
concat = require 'gulp-concat'
sass = require 'gulp-sass'
sourcemaps = require 'gulp-sourcemaps'
merge = require 'merge-stream'
debug = require 'gulp-debug'
coffee = require 'gulp-coffee'
livereload = require 'gulp-livereload'
order = require 'gulp-order'

env = process.env.GULP_ENV
rootPath = 'web/assets/'

paths =
    coffee: [
        'src/Sylius/Bundle/UiBundle/Resources/private/coffee/**',
    ]
    js: [
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/semantic-ui-css/semantic.min.js',
        'src/Sylius/Bundle/ShippingBundle/Resources/public/js/prototype-handler.js',
    ]
    sass: [
        'src/Sylius/Bundle/UiBundle/Resources/private/sass/**',
    ]
    css: [
        'node_modules/semantic-ui-css/semantic.min.css',
    ]
    img: [
        'src/Sylius/Bundle/UiBundle/Resources/private/img/**',
    ]

gulp.task 'js', ->
    jsStream = gulp.src paths.js
        .pipe concat 'javascript-files.js'

    coffeeStream = gulp.src paths.coffee
        .pipe coffee()
        .pipe concat 'coffeescript-files.coffee'

    merge jsStream, coffeeStream
        .pipe order(['javascript-files.js', 'coffeescript-files.coffee'])
        .pipe concat 'app.js'
        .pipe gulpif env is 'prod', uglify
        .pipe sourcemaps.write './'
        .pipe gulp.dest rootPath + 'js'

gulp.task 'css', ->
    cssStream = gulp.src paths.css
        .pipe concat 'css-files.css'

    sassStream = gulp.src paths.sass
        .pipe sass()
        .pipe concat 'sass-files.scss'

    merge cssStream, sassStream
        .pipe order(['css-files.css', 'sass-files.scss'])
        .pipe concat 'style.css'
        .pipe gulpif env is 'prod', uglifycss
        .pipe sourcemaps.write './'
        .pipe gulp.dest rootPath + 'css'
        .pipe livereload()

gulp.task 'img', ->
    gulp.src paths.img
        .pipe sourcemaps.write './'
        .pipe gulp.dest rootPath + 'img'

gulp.task 'watch', ->
    livereload.listen
    gulp.watch paths.js, ['js']
    gulp.watch paths.coffee, ['js']
    gulp.watch paths.sass, ['css']
    gulp.watch paths.css, ['css']
    gulp.watch paths.img, ['img']

gulp.task 'default', ['watch', 'js', 'css', 'img']

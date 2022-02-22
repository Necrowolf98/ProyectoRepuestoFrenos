const mix = require('laravel-mix');

 mix.styles([
    'resources/vendor/css/all.min.css',
    'resources/vendor/css/adminlte.min.css',
    'resources/vendor/css/OverlayScrollbars.min.css',
    'resources/vendor/css/config.css',
], 'public/css/plantilla.css')
.js('resources/js/app.js', 'public/js')
.vue()
.sass('resources/sass/app.scss', 'public/css')
.scripts([
'resources/vendor/js/adminlte.min.js',
'resources/vendor/js/demo.js',
'resources/vendor/js/jquery.overlayScrollbars.min.js',
], 'public/js/plantilla.js')
.copy('resources/vendor/fontawesome/webfonts', 'public/webfonts')
.browserSync('http://importbrands.test/');

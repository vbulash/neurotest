const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Admin
mix.styles([
    'resources/assets/admin/plugins/fontawesome-free/css/all.min.css',
    'resources/assets/admin/plugins/bootstrap/css/bootstrap.min.css',
    'resources/assets/admin/plugins/select2/css/select2.css',
    'resources/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.css',
    'resources/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
    'resources/assets/admin/plugins/lightbox/css/ekko-lightbox.css',
    'resources/assets/admin/css/adminlte.min.css',
    'resources/assets/admin/css/main.css',
    'resources/assets/admin/plugins/toastr/toastr.min.css',
    'resources/css/app.css'
], 'public/assets/admin/css/admin.css');

mix.scripts([
    'resources/assets/admin/plugins/jquery/jquery.min.js',
    'resources/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/assets/admin/plugins/select2/js/select2.full.js',
    'resources/assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    'resources/assets/admin/plugins/lightbox/js/ekko-lightbox.min.js',
    'resources/assets/admin/js/adminlte.min.js',
    'resources/assets/admin/plugins/toastr/toastr.min.js',
    'resources/assets/admin/plugins/pusher/pusher.min.js',
    'resources/assets/admin/js/demo.js'
], 'public/assets/admin/js/admin.js');

mix.copyDirectory('resources/assets/admin/img', 'public/assets/admin/img');
mix.copyDirectory('resources/assets/admin/plugins/fontawesome-free/webfonts', 'public/assets/admin/webfonts');

mix.copy('resources/assets/admin/plugins/jquery/jquery.min.js.map', 'public/assets/admin/plugins/jquery/jquery.min.js.map');
mix.copy('resources/assets/admin/css/adminlte.min.css.map', 'public/assets/admin/css/adminlte.min.css.map');
mix.copy('resources/assets/admin/js/adminlte.min.js.map', 'public/assets/admin/js/adminlte.min.js.map');

// Bootstrap
mix.copy('resources/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js.map', 'public/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js.map');
mix.copy('resources/assets/admin/plugins/bootstrap/css/bootstrap.min.css.map', 'public/assets/admin/plugins/bootstrap/css/bootstrap.min.css.map');

// DatePicker
mix.copy('resources/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css.map', 'public/assets/admin/css/bootstrap-datepicker.css.map');

// DataTables
mix.copy('resources/assets/admin/plugins/datatables/datatables.css', 'public/assets/admin/plugins/datatables/datatables.css');
mix.copy('resources/assets/admin/plugins/datatables/datatables.js', 'public/assets/admin/plugins/datatables/datatables.js');
// http://psycho.bulash.ru/assets/admin/plugins/datatables/bootstrap.js.map
// http://psycho.bulash.ru/assets/admin/plugins/datatables/bootstrap.css.map

// CKEditor5
mix.copy('resources/assets/admin/plugins/ckeditor5-new/build/ckeditor.js', 'public/assets/admin/plugins/ckeditor5/ckeditor.js')
mix.copy('resources/assets/admin/plugins/ckeditor5/sample/styles.css', 'public/assets/admin/plugins/ckeditor5/ckeditor.css')

// Ekko Lightbox
mix.copy('resources/assets/admin/plugins/lightbox/js/ekko-lightbox.min.js.map', 'public/assets/admin/plugins/lightbox/js/ekko-lightbox.min.js.map');

// Toastr
mix.copy('resources/assets/admin/plugins/toastr/toastr.js.map', 'public/assets/admin/js/toastr.js.map');

// Chart.js
mix.copy('resources/assets/admin/plugins/chart.js/chart.min.js', 'public/assets/admin/plugins/chart.js/chart.min.js');

// Parsley
mix.copy('resources/assets/admin/plugins/Parsley.js-2.9.2/dist/parsley.min.js', 'public/assets/admin/plugins/parsley.js/parsley.min.js');
mix.copy('resources/assets/admin/plugins/Parsley.js-2.9.2/dist/parsley.min.js.map', 'public/assets/admin/plugins/parsley.js/parsley.min.js.map');
mix.copy('resources/assets/admin/plugins/Parsley.js-2.9.2/dist/i18n/ru.js', 'public/assets/admin/plugins/parsley.js/i18n/ru.js');
mix.copy('resources/assets/admin/plugins/Parsley.js-2.9.2/dist/i18n/ru.extra.js', 'public/assets/admin/plugins/parsley.js/i18n/ru.extra.js');

// Pickr
mix.copy('resources/assets/admin/plugins/pickr/pickr.min.js', 'public/assets/admin/plugins/pickr/pickr.min.js');
mix.copy('resources/assets/admin/plugins/pickr/pickr.min.js.map', 'public/assets/admin/plugins/pickr/pickr.min.js.map');
mix.copy('resources/assets/admin/plugins/pickr/classic.min.css', 'public/assets/admin/plugins/pickr/classic.min.css');
mix.copy('resources/assets/admin/plugins/pickr/monolith.min.css', 'public/assets/admin/plugins/pickr/monolith.min.css');
mix.copy('resources/assets/admin/plugins/pickr/nano.min.css', 'public/assets/admin/plugins/pickr/nano.min.css');

///////////////////////////////////////////////////////////////////////////////////////////////////
// Front
mix.styles([
    'resources/assets/front/plugins/fontawesome-free/css/all.min.css',
    'resources/assets/front/plugins/bootstrap/css/bootstrap.css',
    'resources/assets/front/plugins/select2/css/select2.css',
    'resources/assets/front/plugins/select2-bootstrap4-theme/select2-bootstrap4.css',
    'resources/assets/front/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
    'resources/assets/front/plugins/toastr/toastr.min.css',
    'resources/assets/front/plugins/pusher/pusher.min.js',
    'resources/assets/front/css/main.css',
], 'public/assets/front/css/front.css');

mix.scripts([
    'resources/assets/front/plugins/jquery/jquery.min.js',
    'resources/assets/front/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/assets/front/plugins/select2/js/select2.full.js',
    'resources/assets/front/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    'resources/assets/front/plugins/toastr/toastr.min.js',
    'resources/assets/front/plugins/pusher/pusher.min.js',
    'resources/assets/front/js/main.js',
], 'public/assets/front/js/front.js');

mix.copyDirectory('resources/assets/front/img', 'public/assets/front/img');
mix.copyDirectory('resources/assets/front/plugins/fontawesome-free/webfonts', 'public/assets/front/webfonts');

mix.copy('resources/assets/front/plugins/bootstrap/js/bootstrap.bundle.min.js.map', 'public/assets/front/plugins/bootstrap/js/bootstrap.bundle.min.js.map');
mix.copy('resources/assets/front/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css.map', 'public/assets/front/css/bootstrap-datepicker.css.map');

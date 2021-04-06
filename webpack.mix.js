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
    'resources/assets/admin/plugins/select2/css/select2.css',
    'resources/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.css',
    'resources/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
    'resources/assets/admin/css/adminlte.min.css',
    'resources/assets/admin/css/main.css',
    'resources/css/app.css'
], 'public/assets/admin/css/admin.css');

mix.scripts([
    'resources/assets/admin/plugins/jquery/jquery.min.js',
    'resources/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/assets/admin/plugins/select2/js/select2.full.js',
    'resources/assets/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    'resources/assets/admin/js/adminlte.min.js',
    'resources/assets/admin/js/demo.js'
], 'public/assets/admin/js/admin.js');

mix.copyDirectory('resources/assets/admin/img', 'public/assets/admin/img');
mix.copyDirectory('resources/assets/admin/plugins/fontawesome-free/webfonts', 'public/assets/admin/webfonts');

mix.copy('resources/assets/admin/css/adminlte.min.css.map', 'public/assets/admin/css/adminlte.min.css.map');
mix.copy('resources/assets/admin/js/adminlte.min.js.map', 'public/assets/admin/js/adminlte.min.js.map');
mix.copy('resources/assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css.map', 'public/assets/admin/css/bootstrap-datepicker.css.map');

// DataTables
mix.copyDirectory('resources/assets/admin/plugins/datatables', 'public/assets/admin/plugins/datatables');

// Front
mix.styles([
    'resources/assets/front/plugins/fontawesome-free/css/all.min.css',
    'resources/assets/front/plugins/bootstrap/bootstrap.css',
    'resources/assets/front/css/main.css',
], 'public/assets/front/css/front.css');

mix.scripts([
    'resources/assets/admin/plugins/bootstrap/bootstrap.bundle.min.js',
], 'public/assets/front/js/front.js');

mix.copyDirectory('resources/assets/front/img', 'public/assets/front/img');
mix.copyDirectory('resources/assets/front/plugins/fontawesome-free/webfonts', 'public/assets/front/webfonts');

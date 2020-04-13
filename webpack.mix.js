let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
	'resources/assets/js/app.js', 
	'resources/assets/js/custom.js',
	'resources/assets/js/credit-request.js',
	'resources/assets/js/tables.js', 
	'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
	'node_modules/jquery-validation/dist/jquery.validate.min.js',
	'node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
	'node_modules/jquery-mask-plugin/dist/jquery.mask.js',
	'node_modules/select2/dist/js/select2.full.js',
	'node_modules/corejs-typeahead/dist/typeahead.jquery.js',
	'node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js',
	'node_modules/chart.js/dist/Chart.bundle.min.js'
	], 'public/js')
	.sass('resources/assets/sass/app.scss', 'public/css')
	.version();   
const mix = require('laravel-mix');

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

mix.js('resources/js/stock.js', 'public/js')
	.js('resources/js/productos.js', 'public/js')
	.js('resources/js/proveedores.js', 'public/js')
	.js('resources/js/clientes.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

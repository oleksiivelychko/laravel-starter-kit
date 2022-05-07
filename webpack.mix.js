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

mix
    .webpackConfig({

    })
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/app_admin.js', 'public/js')
    .sass('resources/css/app.scss', 'public/css')
    .sass('resources/css/admin.scss', 'public/css')
    .sass('resources/css/auth.scss', 'public/css')
    .copyDirectory('storage/app/public/images', 'public/images');

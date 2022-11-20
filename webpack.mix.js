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
    .setPublicPath('public')
    .js('resources/js/app.js', 'js')
    .js('resources/js/dashboard.js', 'js')
    .sass('resources/css/app.scss', 'css')
    .sass('resources/css/dashboard.scss', 'css')
    .sass('resources/css/auth.scss', 'css')
    .copyDirectory('storage/app/public/images', 'public/images')
    .webpackConfig({
        stats: {
            children: true,
        },
    });


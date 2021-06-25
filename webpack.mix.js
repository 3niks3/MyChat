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

mix.js('resources/js/app.js', 'public/js')
    .scripts([
        'resources/js/formPoster.js',
        'resources/js/chatManager.js'
    ],'public/js/all.js')
    .postCss('resources/css/app.css', 'public/css', [
        //require('noty'),
    ])
    .postCss('resources/css/cropper.css', 'public/css')
    .styles('resources/css/noty_theme.css', 'public/css/noty_theme.css')
    .styles([
            'resources/css/custom_styles.css',
            'resources/css/sidebar.css',
    ], 'public/css/all.css');

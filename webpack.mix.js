const mix = require('laravel-mix');
const clean = require('clean-webpack-plugin');

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


mix.webpackConfig({
    plugins: [
        new clean.CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                'css',
                'js',
                'img',
                'vendor',
                'mix-manifest.json'
            ]
        })
    ],
});

mix
    .copyDirectory('resources/img', 'public/img')
    .copyDirectory('resources/css', 'public/css')
    .version();


// Minification
mix.js(['resources/js/cabinet/index/index.js'], 'public/js/cabinet/index/index.min.js').version();
mix.js(['resources/js/cabinet/profile/index.js'], 'public/js/cabinet/profile/index.min.js').version();
mix.js(['resources/js/cabinet/settings/index.js'], 'public/js/cabinet/settings/index.min.js').version();


// Modules
mix.css('app/Modules/Example/resources/cabinet/css/app.css', 'public/css/cabinet/example/app.css');
mix.js(['app/Modules/Example/resources/cabinet/js/app.js'], 'public/js/cabinet/example/app.min.js').version();
mix.copyDirectory('app/Modules/Example/resources/cabinet/img', 'public/img/cabinet/example').version();


// Caching
mix.version('public/css/**/*.css');
mix.version('public/js/**/*.js');


// copy vendor
mix
    .copyDirectory('node_modules/bootstrap/dist', 'public/vendor/bootstrap')

    .copyDirectory('node_modules/jquery/dist', 'public/vendor/jquery')
    .copyDirectory('node_modules/jquery-cropper/dist', 'public/vendor/jquery-cropper')
    .copyDirectory('node_modules/jquery-typeahead/dist', 'public/vendor/jquery-typeahead')
    .copyDirectory('node_modules/jquery-ui-dist', 'public/vendor/jquery-ui-dist')

    .copyDirectory('node_modules/canvas-confetti/dist', 'public/vendor/canvas-confetti')
    .copyDirectory('node_modules/cropperjs/dist', 'public/vendor/cropperjs')

    .copyDirectory('resources/customTools', 'public/vendor/customTools')
;

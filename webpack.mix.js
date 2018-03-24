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

mix.js('resources/assets/js/app.js', 'public/js')
   .extract(['vue'])
   .stylus('resources/assets/stylus/index.styl', 'public/css');

mix.webpackConfig({
    resolve: {
        alias: {
            "@style": path.resolve(__dirname, 'resources/assets/stylus/'),
            "@home": path.resolve(__dirname, 'resources/assets/js/components/home/'),
            "@utils": path.resolve(__dirname, 'resources/assets/js/utils/'),
        }
    }
})

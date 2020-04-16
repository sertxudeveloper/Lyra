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
mix.setPublicPath('publishable/assets');
mix.sass('resources/assets/sass/main.scss', 'css')
  .sass('resources/assets/sass/theme/default.scss', 'css')
  .sass('resources/assets/sass/theme/dark.scss', 'css')
  .sass('resources/assets/sass/theme/light.scss', 'css')
  .js('resources/assets/js/app.js', 'js');

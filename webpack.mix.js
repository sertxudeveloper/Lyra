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
// mix.setPublicPath('.');
mix.sass('resources/assets/sass/login.scss', 'publishable/assets/css')
  .sass('resources/assets/sass/main.scss', 'publishable/assets/css')
  .sass('resources/assets/sass/theme/default.scss', 'publishable/assets/css')
  .sass('resources/assets/sass/theme/dark.scss', 'publishable/assets/css')
  .sass('resources/assets/sass/theme/light.scss', 'publishable/assets/css')
  .js('resources/assets/js/app.js', 'publishable/assets/js');

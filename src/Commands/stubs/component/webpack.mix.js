const mix = require('laravel-mix');

mix.setPublicPath('dist')
  .js('resources/js/component.js', 'js')
  .sass('resources/sass/component.scss', 'css');

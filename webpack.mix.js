const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

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
mix.setPublicPath('publishable/assets')

mix.js('resources/js/app.js', 'js')
  .postCss('resources/css/app.css', 'css', [tailwindcss('tailwind.config.js')])


const mix = require('laravel-mix')
const path = require('path')
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

mix.sass('resources/sass/app.scss', 'css');
mix.js('resources/js/app.js', 'js').vue();

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js/'),
    },
  },
})

mix.options({
  processCssUrls: false, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
  purifyCss: false, // Remove unused CSS selectors.
  postCss: [tailwindcss('tailwind.config.js')],
});

const mix = require('laravel-mix');

//require('laravel-mix-bundle-analyzer');

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

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps()
   .browserSync({ proxy: 'localhost:8000' })
   .options({
      legacyNodePolyfills: true
   });
// Disabled by default because it is very slow...
// if (mix.isWatching()) {
//   mix.bundleAnalyzer();
// }

const mix = require('laravel-mix');
require('laravel-mix-bundle-analyzer');
require('laravel-mix-ignore');

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

mix.webpackConfig({
    externals: {
        jquery: 'jQuery',
        plotly: 'Plotly',
    },
});

mix.bundleAnalyzer({
    openAnalyzer: false,
    analyzerMode: 'static',
    generateStatsFile: false,
});

mix.ignore(
    /^\.\/locale$/,
    /moment$/
);

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/plotting.js', 'public/js')
   .js('resources/js/form.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .extract();

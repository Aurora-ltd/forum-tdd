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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .version();


/**
 * WDS setting
 */
mix.options({
hmrOptions: {
    host: 'localhost',
    port: 3080
}
});

mix.webpackConfig({
devServer: {
    host: '0.0.0.0',
    port: 3080,
    proxy: {
    '/': 'http://webserver'
    }
}
});

mix.browserSync({
    files: [
        'resources/views/**/*.blade.php',
        'public/css/**/*.css'
    ],
    watchOptions: {
        usePolling: true,
        interval: 2000
    },
    open: false,
    port: 3000,
    ui: {
        port: 3001
    },
    proxy: 'http://localhost:3080'
});

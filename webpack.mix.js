const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('Assets').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/cms.js')
    .sass( __dirname + '/Resources/assets/scss/app.scss', 'css/cms.css');

if (mix.inProduction()) {
    mix.version();
}
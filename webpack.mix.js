const mix = require("laravel-mix");

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

mix.js("resources/js/app.js", "public/js")
    .js("resources/js/index.js", "public/js")
    .js("resources/js/profile.js", "public/js")
    .js("resources/js/register.js", "public/js")
    .js("resources/js/register-seller.js", "public/js")
    .js("resources/js/home.js", "public/js")
    .js("resources/js/product.js", "public/js")
    .js("resources/js/product-create.js", "public/js")
    .js("resources/js/product-update.js", "public/js")
    .js("resources/js/product-info.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .copy("node_modules/leaflet/dist/images", "public/css/images")
    .sourceMaps();

const mix = require('laravel-mix');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const publicDirectory = 'public/';
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


mix.copy('node_modules/font-awesome/fonts', publicDirectory + 'css/fonts')
    .copy('node_modules/ionicons/dist/fonts', publicDirectory + 'css/fonts');


mix.js('resources/js/mainScripts/site.js', publicDirectory + 'js/site/app.js')
    .js('resources/js/mainScripts/admin.js', publicDirectory + 'js/admin/app.js')
    .sass('resources/sass/site/app.scss', publicDirectory + 'css/site/app.css')
    .sass('resources/sass/admin/app.scss', publicDirectory + 'css/admin/app.css');
//mix.babel(),mix.minify('path/to/file.js');
mix.options({//https://laravel-mix.com/docs/2.1/options
    cleanCss: {
        level: {
            1: {
                specialComments: 'none'
            }
        }
    },
    processCssUrls: false,
    autoprefixer: {
        options: {
            browsers: [
                'last 3 versions',
            ]
        }
    },
    // postCss: [
    //     require('autoprefixer')({
    //         browsers: ['last 2 versions'],
    //         cascade: false
    //     })
    // ],
    purifyCss: false
});


if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
    // mix.browserSync('baseAdmin.test');
}

//mix.autoload({
//    jquery: ['$', 'window.jQuery']
// });
// Add this to very bottom of your webpack-mix.js
mix.webpackConfig({
    resolve: {
        // modules: [path.resolve(__dirname, '../../../node_modules')],
        alias: {
            jquery: "jquery/src/jquery",
            pace: 'pace-progress'
        }
    },
    node: {
        fs: "empty"
    },
    plugins: [
        new CleanWebpackPlugin([publicDirectory + 'js', publicDirectory + 'css', publicDirectory + 'fonts'])//prosoxi katharizei ta panta apo to public
    ]
});

const mix = require('laravel-mix');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const assetsDirectory = path.normalize('resources/assets/');
const publicDirectory = path.normalize('./filesToPublish/assets/');

mix.setPublicPath('./');
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


mix.copy('node_modules/font-awesome/fonts', publicDirectory + 'css/fonts');
mix.copy(assetsDirectory + '/images', publicDirectory + 'images');

mix.js(assetsDirectory + 'js/mainScripts/admin.js', publicDirectory + 'js/admin/app.js');
mix.js(assetsDirectory + 'js/mainScripts/site.js', publicDirectory + 'js/site/app.js');
mix.sass(assetsDirectory + 'sass/site/app.scss', publicDirectory + 'css/site/app.css');
mix.sass(assetsDirectory + 'sass/admin/app.scss', publicDirectory + 'css/admin/app.css');
//mix.babel(),mix.minify('path/to/file.js');
mix.options({//https://laravel-mix.com/docs/2.1/options
    postCss: [
        require('autoprefixer')
    ],
    cssNano: {
        discardComments: {removeAll: true},
        discardDuplicates: true
    },
    processCssUrls: false,
    autoprefixer: {
        options: {
            browsers: ['last 3 versions']
        }
    },
    purifyCss: false
});


if (mix.inProduction()) {
    mix.version()
        .options({
            // Optimize JS minification process
            terser: {
                cache: true,
                parallel: true,
                sourceMap: true
            }
        });
} else {
    mix.sourceMaps();
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
    //l mix.browserSync('baseAdmin.test');
}


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
        new CleanWebpackPlugin({verbose: true, cleanOnceBeforeBuildPatterns: [publicDirectory + '**/*']})//prosoxi katharizei ta panta apo to public
    ]
});

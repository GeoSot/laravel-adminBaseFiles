const mix = require('laravel-mix');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const assetsDirectory = path.normalize('resources/assets/');
const publicDirectory = path.normalize('./filesToPublish/assets/');
// mix.setPublicPath('public/assets')
// mix.setResourceRoot('/assets/');
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
    processCssUrls: false,
    cssNano: {
        discardComments: {removeAll: true},
        discardDuplicates: true,
        mergeIdents: true
    },
    terser: { //https://github.com/webpack-contrib/terser-webpack-plugin#options
        // cache: true, //Doesn't work with webpack 5!
        parallel: true, //If you use Circle CI or any other environment that doesn't provide real available count of CPUs then you need to setup explicitly number of CPUs to avoid
        // sourceMap: true
    },
});


if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}
mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
});




// Add this to very bottom of your webpack-mix.js
mix.webpackConfig({
    output: {
        chunkFilename: publicDirectory+"js/chunks/[name].chunk.[hash].js",
        publicPath: `/assets/`
    },
    resolve: {
        // modules: [path.resolve(__dirname, '../../../node_modules')],
        alias: {
            jquery: "jquery/src/jquery",
            pace: 'pace-progress'
        }
    },
    plugins: [
        new CleanWebpackPlugin({cleanOnceBeforeBuildPatterns: [publicDirectory + '**/*']})//prosoxi katharizei ta panta apo to public
    ]
});

const mix = require('laravel-mix');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');

require('laravel-mix-versionhash')
require('laravel-mix-bundle-analyzer');

if (!mix.inProduction()) {
    // mix.bundleAnalyzer();
}
const resourcesDirectory = './resources/';
const publicDirectory = './assets/';
// mix.setResourceRoot('/assets/');
mix.setPublicPath(publicDirectory);


mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', publicDirectory + 'fonts');

mix.js(resourcesDirectory + 'js/mainScripts/admin.js', publicDirectory + 'js/admin.js').vue({ version: 2 });
mix.js(resourcesDirectory + 'js/mainScripts/site.js', publicDirectory + 'js/app.js');
mix.sass(resourcesDirectory + 'css/admin/app.scss', publicDirectory + 'css/admin.css');
mix.sass(resourcesDirectory + 'css/site/app.scss', publicDirectory + 'css/app.css');


mix.options({
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
//     mix.version();
    mix.versionHash();

} else {
    mix.sourceMaps();
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}
mix.babelConfig({
    "plugins": [
        '@babel/plugin-syntax-dynamic-import',
        "@babel/plugin-proposal-class-properties"
    ]
});


// Add this to very bottom of your webpack-mix.js
mix.webpackConfig({
    output: {
        chunkFilename: "js/chunks/[name].chunk.[hash].js",
    },
    resolve: {
        // modules: [path.resolve(__dirname, '../../../node_modules')],
        alias: {
            jquery: "jquery/src/jquery",
            pace: 'pace-progress',
        }
    },
    plugins: [
        new MomentLocalesPlugin({localesToKeep: ['en-gb', 'el'],}),
        new CleanWebpackPlugin({cleanStaleWebpackAssets: false,})
    ]
});

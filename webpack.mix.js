const mix = require('laravel-mix');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const resourcesDirectory = './resources/';
const publicDirectory = 'assets/';
// mix.setPublicPath('public/assets')
// mix.setResourceRoot('/assets/');
mix.setPublicPath('.\\');

mix.copy('node_modules/font-awesome/fonts', publicDirectory + 'fonts');
//

mix.js(resourcesDirectory + 'js/mainScripts/admin.js', publicDirectory + 'js/admin.js');
mix.js(resourcesDirectory + 'js/mainScripts/site.js', publicDirectory + 'js/app.js');
mix.sass(resourcesDirectory + 'css/admin/app.scss', publicDirectory + 'css/admin.css');
mix.sass(resourcesDirectory + 'css/site/app.scss', publicDirectory + 'css/app.css');
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
    // plugins: ['@babel/plugin-syntax-dynamic-import'],
    "plugins": ["@babel/plugin-proposal-class-properties"]
});


// Add this to very bottom of your webpack-mix.js
mix.webpackConfig({
    /* entry: {
        "assets/admin": resourcesDirectory + 'css/admin/app.scss',
         app:  resourcesDirectory + 'css/site/app.scss'
     },*/
    // output: {
    //     chunkFilename:publicDirectory + "js/chunks/[name].chunk.[hash].js",
    //     publicPath: `/`
    // },
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

const path = require('path')
// const fs = require('file-system')
const mix = require('laravel-mix')
const CompressionPlugin = require('compression-webpack-plugin');

// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix.setPublicPath(path.normalize('./public/dist/js'))

mix
    .js('resources/js/app.js', path.join(__dirname, './public/dist/js'))
    .js('resources/js/admin.js', path.join(__dirname, './public/dist/js'))
    .disableNotifications()

if (mix.inProduction()) {
    require('laravel-mix-versionhash')
    mix
        // .extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
        // .version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.

        // mix.extract([
        //     'vue',
        //     'vform',
        //     'axios',
        //     'vuex',
        //     'jquery',
        //     'popper.js',
        //     'vue-meta',
        //     'js-cookie',
        //     'bootstrap',
        //     'vue-router',
        //     'vuex-router-sync',
        //     'linq',
        //     'vonic',
        //     'moment'
        // ])
        .versionHash()
} else {
    mix.sourceMaps()
}

mix.webpackConfig({
    plugins: [
        new CompressionPlugin({
            filename: '[path][base].gz',
            algorithm: 'gzip',
            test: /\.js$|\.css$|\.html$|\.svg$/,
            threshold: 10240,
            minRatio: 0.8,
        })
    ],
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '~': path.join(__dirname, './resources/js'),
            'VueRouter': 'vue-router',
            'Vue': 'vue',
            '#': path.resolve(__dirname, './resources/sass')
        }
    },
    output: {
        chunkFilename: '../../chunk/[name].js',
    }
})
    .options({
        postCss: [
            require('autoprefixer'),
        ],
        terser: {
            terserOptions: {
                keep_fnames: true,
                keep_classnames: true,
                compress:{ pure_funcs: ['console.info', 'console.debug', 'console.warn'] }
            }
        }
    })

if (mix.inProduction()) {
    mix.version();
}


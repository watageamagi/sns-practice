const mix = require('laravel-mix')
const path = require('path')
const CompressionPlugin = require('compression-webpack-plugin');

mix.setPublicPath(path.normalize('./public/dist/css'))

mix
    .sass('resources/sass/admin.scss', path.join(__dirname, './public/dist/css'))
    .sass('resources/sass/app.scss', path.join(__dirname, './public/dist/css'))
    .disableNotifications()

if (mix.inProduction()) {
    require('laravel-mix-versionhash')
    mix
    // .extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
    // .version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.
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
        alias: {
            '#': path.resolve(__dirname, './resources/sass')
        }
    },
})
    .options({
        processCssUrls: true
    })

if (mix.inProduction()) {
    mix.version();
}

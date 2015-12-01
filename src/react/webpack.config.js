var webpack = require('webpack');

var appName = 'play';
var outputFile = appName + '.js';

var config = {
    entry: './src/play.js',
    devtool: 'source-map',
    output: {
        path: '/var/www/public/w/js/react',
        filename: outputFile,
        publicPath: '/w/js/react'
    },
    module: {
        loaders: [
            {
                test: /(\.jsx|\.js)$/,
                loader: 'babel',
                exclude: /(node_modules|bower_components)/,
                query: {
                    presets: ['react']
                }
            }
        ]
    },
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    }
};

module.exports = config;
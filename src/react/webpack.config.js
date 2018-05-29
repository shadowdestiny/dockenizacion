var webpack = require('webpack');
var UglifyJsPlugin = webpack.optimize.UglifyJsPlugin;
var env = process.env.WEBPACK_ENV;

var path = require('path');

var plugins = [], outputFile;

if (env == 'build') {
    plugins.push(new UglifyJsPlugin({minimize: true}));
    outputFile = '.js';
} else {
    outputFile = '.js';
}

var config = {
    entry: {
       play : './src/play.jsx',
       cart : './src/cart.jsx',
       tooltip : './src/tooltip.jsx',
    },
    devtool: 'source-map',
    output: {
        path: '../public/w/js/react',
        filename: '[name]'+outputFile,
        publicPath: '/w/js/react'
    },
    module: {
        loaders: [
            {
                test: /(\.jsx|\.js)$/,
                loader: 'babel-loader',
                exclude: /(node_modules|bower_components)/,
                query: {
                    presets: ['react','es2015', 'babel-preset-stage-0']
                }
            },
            //{
            //    test: /(\.jsx|\.js)$/,
            //    loader: "eslint-loader",
            //    exclude: /node_modules/
            //}
        ],
        noParse: [
            /node_modules\/sinon/,
        ]
    },
    plugins: plugins,
    resolve: {
        root: path.resolve('./src'),
        extensions: ['', '.js', '.jsx']
    },
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    }
};
module.exports = config;

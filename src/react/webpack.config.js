const path = require('path');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");

const outputFile = '.js';
const env = process.env.WEBPACK_ENV;

let isDevelop = (env === 'development');
let mode;

let minimize = [];

if (!isDevelop){
    mode = 'production';
    minimize.push(new UglifyJsPlugin({
        cache: true,
        parallel: false,
        uglifyOptions: {
            compress: false,
            //ecma: 6,
            mangle: true
        },
        sourceMap: true
    }));
    /*minimize.push(new OptimizeCSSAssetsPlugin({
        cssProcessorPluginOptions: {
            preset: ['default', { discardComments: { removeAll: true } }],
        }
    }));*/
} else {
    mode = 'development';
    minimize.push(new UglifyJsPlugin({
        cache: true,
        parallel: false,
        uglifyOptions: {
            compress: true,
            // ecma: 6,
            mangle: true
        },
        sourceMap: true
    }));
}

let react_module = {
    mode: mode,
    watch:isDevelop,
    optimization: {
        minimizer: minimize
    },
    entry: {
        play : './src/play.jsx',
        cart : './src/cart.jsx',
        tooltip : './src/tooltip.jsx',
    },
    output: {
        path: path.resolve(__dirname, '../public/w/js/react'),
        filename: '[name]'+outputFile,
        publicPath: '/w/js/react'
    },
    module: {
        rules: [
            {
                test: /(\.jsx|\.js)$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            '@babel/preset-env',
                            '@babel/preset-react',
                            {
                                plugins: [
                                    '@babel/plugin-proposal-class-properties',
                                ]
                            }
                        ]
                    }
                }
            }
        ]
    },
    resolve: {
        extensions: ['*', '.js', '.jsx']
    }
};

let sass_module = {
    mode: mode,
    watch:isDevelop,
    stats: {
        warnings: false
    },
    optimization: {
        minimizer: minimize
    },
    entry: {
        "main_v2": path.resolve(__dirname, '../compass_web/sass') + "/main_v2.scss"
    },
    output: {
        path: path.resolve(__dirname, '../public/w/css'),
        filename: './compiled/[name].compiled',
        publicPath:  path.resolve(__dirname, '/public/w/css'),
    },
    module: {
        rules: [{
            test: /\.scss$/,
            use: [
                {
                    loader: "style-loader",
                },
                {
                    loader: MiniCssExtractPlugin.loader
                },
                {
                    loader: "css-loader?-url",
                },
                {
                    loader: "sass-loader",
                }
            ]
        }]
    },
    plugins: [
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({
            //filename: "[name].[chunkhash:8].css",
            filename: "[name].css",
        }),
    ],
};

module.exports = [
    react_module,
    //sass_module
];
var webpackConfig = require('./webpack.config');
webpackConfig.devtool = 'inline-source-map';

module.exports = function (config) {
    config.set({
        browsers: ['PhantomJS'],
        singleRun: true,
        frameworks: [ 'mocha'],
        files: [
            'tests.webpack.js'
        ],
        plugins: [
            'karma-phantomjs-launcher',
            'karma-chai',
            'karma-mocha',
            'karma-sourcemap-loader',
            'karma-webpack',
            'karma-mocha-reporter',
            'karma-sinon',
            'karma-sinon-chai'
        ],
        preprocessors: {
            'tests.webpack.js': [ 'webpack', 'sourcemap' ]
        },
        reporters: [ 'mocha' ],
        resolve: {
            alias: {
                sinon: 'sinon/pkg/sinon.js'
            }
        },
        webpack: webpackConfig,
        webpackServer: {
            noInfo: true
        },
        autoWatch: true
    });
};
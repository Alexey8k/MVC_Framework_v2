let path = require('path');
let webpack = require('webpack');

module.exports = {
    entry: {
        index: './js/src/index',
    },
    output: {
        filename: 'build.js',
        path: path.resolve(__dirname, 'js/dist')
    },

    watch: true,

    watchOptions: {
        aggregateTimeout: 100
    },

    module: {
        rules: [
            {
                test: /\.js?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['@babel/preset-react','@babel/preset-env'],
                    plugins: ["@babel/plugin-proposal-class-properties"]
                }
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
        ]
    },
    // resolve: {
    //     modules: ['node_modules'],
    //     extensions: [".js", ".json", ".jsx", ".css"]
    // },

};
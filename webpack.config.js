const path = require('path');
const pkg = require('./package.json');

module.exports = {
  mode: process.env.NODE_ENV || 'development',
  context: path.resolve(__dirname, 'assets'),
  entry:  [
    './js/contact-form.js',
    './css/contact-form.css'
  ],
  output: {
    filename: 'contact-form.js',
    auxiliaryComment: pkg.name
  },
  module: {
    rules: [{
      test: /\.js$/,
      exclude: /node_modules/,
      use: {
        loader: 'babel-loader',
        options: {
          presets: [ '@babel/preset-env' ],
          plugins: [
            // '@babel/transform-runtime',
            '@babel/plugin-transform-spread'
          ]
        }
      }
    }, {
      test: /\.css$/,
      use: [
        {
          loader: 'file-loader',
          options: {
            name: '[name].css',
            emitFile: true
          }
        },
        'extract-loader',
        'css-loader',
        'postcss-loader'
      ]
    }]
  }
};

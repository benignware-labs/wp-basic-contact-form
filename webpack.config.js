const path = require('path');
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = [
  {
    ...defaultConfig,
    entry: {
      blocks: path.resolve( process.cwd(), 'src', 'index' )
    },
    output: {
      filename: '[name].js',
      path: path.resolve( process.cwd(), 'dist/blocks' )
    }
  }
];

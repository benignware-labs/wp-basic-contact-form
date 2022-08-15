
console.log('postcss config');

module.exports = ({ file, options, env }) => {
  console.log('env', env, process.env.NODE_ENV, options);

  return ({
    parser: file.extname === '.sss' ? 'sugarss' : false,
    plugins: {
      // 'postcss-import': { root: file.dirname },
      'postcss-preset-env': options['postcss-preset-env'] ? options['postcss-preset-env'] : false,
      'autoprefixer': {},
      'cssnano': env === 'production' ? {} : false
    }
  });

};

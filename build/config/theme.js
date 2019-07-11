module.exports = (env, argv, home) => {
  // DEPENDENCIES
  const LoaderTransform = require('../component-loader-transformer');
  const CopyWebpackPlugin = require(home + '/node_modules/copy-webpack-plugin');

  return {
    entry: {
      'theme': ['@babel/polyfill/noConflict', './_src/scripts/theme.js', './_src/styles/theme.scss'],
    },

    plugins: [
      new CopyWebpackPlugin([
        { from: '../pf-parent/build/component-loader.js', to: '../_src/scripts/frontend/', transform(content) { return LoaderTransform(content) } },
      ]),
    ]
  };
};

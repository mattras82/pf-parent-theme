const path = require('path');

module.exports = (env, argv, home) => {
  // DEPENDENCIES
  const CopyWebpackPlugin = require(home + '/node_modules/copy-webpack-plugin');
  const OptimizillaPlugin = require(home + '/node_modules/optimizilla-webpack-plugin');
  const LastCallPlugin = require(home + '/node_modules/last-call-webpack-plugin');

  return {
    plugins: [
      new OptimizillaPlugin({
        src: path.resolve(home + '/_src/images')
      }),
      new CopyWebpackPlugin([
        { from: home + '/_src/images', to: 'images', ignore: ['*.json'] },
        { from: home + '/node_modules/jquery/dist/jquery.min.js' },
        { from: home + '/node_modules/@fortawesome/fontawesome-free/webfonts', to: 'fonts' },
      ]),
      new LastCallPlugin({
        assetProcessors: [{
          regExp: /dummy/,
          processor: (assetName, asset, assets) => {
            assets.setAsset('dummy.js', null);
            return Promise.resolve();
          }
        }]
      })
    ]
  };
};

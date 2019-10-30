module.exports = (env, argv, home) => {
  // DEPENDENCIES
  const CopyWebpackPlugin = require(home + '/node_modules/copy-webpack-plugin');
  const ImageminPlugin = require(home + '/node_modules/imagemin-webpack-plugin').default;
  const LastCallPlugin = require(home + '/node_modules/last-call-webpack-plugin');

  return {
    plugins: [
      new CopyWebpackPlugin([
        { from: home + '/_src/images', to: 'images' },
        { from: home + '/node_modules/jquery/dist/jquery.min.js' },
        { from: home + '/node_modules/@fortawesome/fontawesome-free/webfonts', to: 'fonts' },
      ]),
      new ImageminPlugin({
        test: /\.(gif|jpe?g|png|svg)$/,
        gifsicle: {
          interlaced: true,
          optimizationLevel: 3,
        },
        jpegtran: { progressive: true },
        optipng: { optimizationLevel: 7 },
      }),
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

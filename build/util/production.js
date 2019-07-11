module.exports = (env, argv, home) => {
  // PLUGINS
  const OptimizeCssAssetsPlugin = require(home + '/node_modules/optimize-css-assets-webpack-plugin');
  const CopyWebpackPlugin = require(home + '/node_modules/copy-webpack-plugin');
  const PFNotificationPlugin = require('../notification-plugin');

// CONTENT TRANSFORMERS
  const SWTransform = require('../service-worker-transformer');
  const ManifestTransform = require('../manifest-transformer');
  const OfflineTransform = require('../offline-transformer');

  return  {
    plugins: [
      new PFNotificationPlugin(home),
      new CopyWebpackPlugin([
        { from: '../pf-parent/build/manifest.json', transform(content) { return ManifestTransform(content, home) } },
        { from: '../pf-parent/build/sw.js', to: '../../../../', transform(content) { return SWTransform(content) } },
        { from: '../pf-parent/build/offline.html', to: '../../../../', transform(content) { return OfflineTransform(content) } },
      ]),
      new OptimizeCssAssetsPlugin({
        cssProcessorOptions: { discardComments: { removeAll: true } },
      }),
    ],
  };
};


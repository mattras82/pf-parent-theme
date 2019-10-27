const fs = require('fs');
const path = require('path');

module.exports = (env, argv, home) => {
  // PLUGINS
  const OptimizeCssAssetsPlugin = require(home + '/node_modules/optimize-css-assets-webpack-plugin');
  const CopyWebpackPlugin = require(home + '/node_modules/copy-webpack-plugin');
  const PFNotificationPlugin = require('../notification-plugin');

// CONTENT TRANSFORMERS
  const SWTransform = require('../service-worker-transformer');
  const ManifestTransform = require('../manifest-transformer');
  const OfflineTransform = require('../offline-transformer');

  let prefix = '../pf-parent-theme';
  let copyFiles = [
    {from: '/build/manifest.json', transform(content) { return ManifestTransform(content, home) }},
    {from: '/build/sw.js', transform(content) { return SWTransform(content) }},
    {from: '/build/offline.html', transform(content) { return OfflineTransform(content) }},
  ];

  copyFiles = copyFiles.map(item => {
    try {
      let childFileExists = fs.readFileSync(home + item.from);
      item.from = home + item.from;
    } catch(e) {
      item.from = prefix + item.from;
    }
    return item;
  });

  return  {
    plugins: [
      new PFNotificationPlugin(home),
      new CopyWebpackPlugin(copyFiles),
      new OptimizeCssAssetsPlugin({
        cssProcessorOptions: { discardComments: { removeAll: true } },
      }),
    ],
  };
};


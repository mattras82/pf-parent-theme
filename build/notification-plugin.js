const fs = require('fs');
const path = require('path');

class NotificationPlugin {
  constructor(home) {
    this.home = home;
  }

  run(results) {
    const notifier = require(this.home + '/node_modules/node-notifier');
    if (!results.hasErrors()) {
      const manifest = JSON.parse(fs.readFileSync('./assets/manifest.json'));
      const config = JSON.parse(fs.readFileSync('./config/config.json'));
      let icon = path.join(this.home, 'assets/images/' + config.styles.icon.name + '-192.png');
      notifier.notify({
        title: manifest.short_name + ' Build Completed',
        message: `Please be sure to upload the sw.js & config/config.json files along with the minified JS & CSS files.`,
        icon: icon,
        wait: true,
        timeout: 10
      });
    }
  }

  apply(compiler) {
    compiler.hooks.done.tap('webpack-build-notifier', this.run.bind(this));
  }
}

module.exports = NotificationPlugin;

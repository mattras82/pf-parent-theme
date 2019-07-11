const fs = require('fs');

class ConfigBuild {

  processDate(date) {
    let year = date.getFullYear().toString();
    let month = date.getMonth().toString();
    let day = date.getDay().toString();
    let hour = date.getHours().toString();
    let minutes = date.getMinutes().toString();
    let seconds = date.getSeconds().toString();
    let timestamp = Number(year + month + day + hour + minutes + seconds);
    return timestamp.toString(36);
  }

  run(env) {
    const config = JSON.parse(fs.readFileSync('./config/config.json'));
    if (env === 'production' || (env === 'development' && config.env.production)) {
      let compiled = Object.assign({}, config, {
        env: {
          development: env !== 'production',
          production: env === 'production'
        }
      });

      if (env === 'production') {
        let timestamp = this.processDate(new Date());

        if (timestamp) {
          compiled.build = timestamp;
        }
      }

      fs.writeFileSync('./config/config.json', JSON.stringify(compiled, null, 2));
    }
  }

  apply(compiler) {
    compiler.hooks.beforeCompile.tap('ConfigBuild', (compilation, callback) => {
      this.run(compiler.options.mode);
    });
  }
}

module.exports  = ConfigBuild;

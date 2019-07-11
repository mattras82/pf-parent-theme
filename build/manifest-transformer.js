const fs = require('fs');

module.exports = function (source, home) {
  const sharp = require(home + '/node_modules/sharp');
  const config = JSON.parse(fs.readFileSync('./config/config.json'));
  let merged = Object.assign({}, {
    'version': config.version,
    'theme_color': config.styles.sass.theme_color,
    'background_color': config.styles.sass.theme_color,
    'short_name': config.theme.short_name,
    'name': config.theme.name
  }, JSON.parse(source.toString()));
  let path = config.styles.icon.path,
    name = config.styles.icon.name,
    dest = '/wp-content/themes/' + config.theme.short_name + '/assets/images/';
  try {
    let smallIcon = fs.readFileSync(path + name + '-270.png');
  } catch (e) {
    if (config.styles.icon) {
      sharp(path + name + '.png')
        .resize(270)
        .toFile(path + name + '-270.png', (err, info) => {
          if (err) console.log(err);
        });
      sharp(path + name + '.png')
        .resize(192)
        .toFile(path + name + '-192.png', (err, info) => {
          if (err) console.log(err);
        });
      sharp(path + name + '.png')
        .resize(180)
        .toFile(path + name + '-180.png', (err, info) => {
          if (err) console.log(err);
        });
      sharp(path + name + '.png')
        .resize(32)
        .toFile(path + name + '-32.png', (err, info) => {
          if (err) console.log(err);
        });
    }
  }
  merged = Object.assign({}, merged, {
    "icons": [
      {
        "src": dest + name + "-32.png",
        "type": "image/png",
        "sizes": "32x32"
      },
      {
        "src": dest + name + "-192.png",
        "type": "image/png",
        "sizes": "192x192"
      },
      {
        "src": dest + name + ".png",
        "type": "image/png",
        "sizes": "512x512"
      }
    ]
  });
  return JSON.stringify(merged, null, 2);
};

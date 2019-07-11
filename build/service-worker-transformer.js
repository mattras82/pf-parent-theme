const fs = require('fs');

let content = null;
let config = null;

function replaceShortName() {
  content = content.replace(/\[short_name]/g, `${config.theme.short_name}-${config.build}`);
}

function addUrlsToCache() {
  let urls = [
    '/offline.html',
    `/wp-content/themes/${config.theme.short_name}/assets/images/logo.png`,
    `/wp-content/themes/${config.theme.short_name}/assets/jquery.min.js`,
    `/wp-content/themes/${config.theme.short_name}/assets/theme.css?ver=${config.build}`,
    `/wp-content/themes/${config.theme.short_name}/assets/theme.js?ver=${config.build}`,
  ];
  let string = `const urlsToCache = [\n\tlocation.origin,${urls.reduce((acc,url) => acc += `\n\tlocation.origin + '${url}',`, '')}\n]`;
  content = content.replace(/\[urlsToCache]/, string);
}

function processContent() {
  replaceShortName();
  addUrlsToCache();
}

module.exports = function (original) {
  content = original.toString();
  config = JSON.parse(fs.readFileSync('./config/config.json'));
  processContent();
  return content;
};

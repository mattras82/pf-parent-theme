const fs = require('fs');

let content = null;
let config = null;

function getConfigVal(name) {
  let path = name.split('.');
  let val = config;
  path.forEach(p => {
    if (val.hasOwnProperty(p)) val = val[p];
  });
  if (typeof val === 'string')
    return val;
  return '';
}

function insertConfigVal(start = 0) {
  if (content.indexOf('[', start) > -1 && start < content.length) {
    start = content.indexOf('[', start);
    let end = content.indexOf(']', start);
    let path = content.substring(start+1, end);
    let val = getConfigVal(path);
    let regEx = new RegExp(`\\[${path}\\]`, 'g');
    content = content.replace(regEx, val);
    start++;
    insertConfigVal(start);
  }
  return true;
}

function processContent() {
  insertConfigVal();
}

module.exports = function (original) {
  content = original.toString();
  config = JSON.parse(fs.readFileSync('./config/config.json'));
  processContent();
  return content;
};

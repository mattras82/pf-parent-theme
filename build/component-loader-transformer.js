const fs = require('fs');

let content = null;
let config = null;

function updateExcludes() {
  let excludedCompents = '',
    components = config.styles.sass.components;

  for (let component in components) {
    if (components.hasOwnProperty(component) && components[component] !== true) {
      excludedCompents += component + '|';
    }
  }

  if (excludedCompents) {
    excludedCompents = '/' + excludedCompents.slice(0, -1) + '/';
  } else {
    excludedCompents = '';
  }

  content = content.replace(/\[exclude_list]/, excludedCompents);
}

function processContent() {
  updateExcludes();
}

module.exports = function (original) {
  content = original.toString();
  config = JSON.parse(fs.readFileSync('./config/config.json'));
  processContent();
  return content;
};

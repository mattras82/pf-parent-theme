import config from '../../../config/config';

let components = [];

for (let component in config.styles.sass.components) {
  if (config.styles.sass.components.hasOwnProperty(component) &&
    config.styles.sass.components[component] === true) {
    import(
      /* webpackMode: "eager" */
      /* webpackExclude: [exclude_list] */
      `./components/${component}`).then(module => components.push(module));
  }
}

function initComponents() {
  components.forEach(component => {
    if (component.activeSelector) {
      let items = document.querySelectorAll(component.activeSelector);
      if (items && items.length > 0) {
        let init = component.init(items);
        if (init && init.catch) {
          init.catch(e => console.warn(`Error in ${component.activeSelector} module:\n${e}`));
        }
      }
    }
  });
}

document.addEventListener('DOMContentLoaded', initComponents);

module.exports = (env, argv, home) => {
  // HELPERS
  const path = require('path');

  // PLUGINS
  const MiniCssExtractPlugin = require(home + '/node_modules/mini-css-extract-plugin');

  // USER CONFIG
  const config = require(home + '/config/config');

  function issuer(m) {
    return (m.issuer ? issuer(m.issuer) : (m.name ? m.name : false));
  }

  function configSassVariables() {
    let sass = config.styles.sass;

    if (!(typeof sass === 'object' && sass !== null)) return '';

    return Object.keys(sass).reduce((variables, variable) => {
      if (typeof sass[variable] === 'string') {
        variables += `$${variable}:${sass[variable]};`;
      } else if (typeof sass === 'object' && sass !== null) {
        variables += `$${variable}:(${Object.keys(sass[variable]).reduce((_val, _var) => _val += `${_var}:${sass[variable][_var]},`, '')});`
      }
      return variables;
    }, '');
  }

  return {
    module: {
      rules: [
        {
          test: /\.s?css$/,
          use: [
            MiniCssExtractPlugin.loader,
            { loader: 'css-loader', options: { url: false, sourceMap: true} },
            { loader: 'postcss-loader', options: { sourceMap: true } },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: true,
                data: configSassVariables(),
              }
            },
          ]
        }
      ]
    },

    plugins: [
      new MiniCssExtractPlugin({
        filename: '[name].css'
      })
    ],

    // Split CSS into separate files based on entry point.
    optimization: {
      splitChunks: {
        cacheGroups: {
          themeCss: {
            name: 'theme',
            test: (m,c,entry = 'theme') => m.constructor.name === 'CssModule' && issuer(m) === entry,
            chunks: 'all',
            enforce: true,
          },
          adminCss: {
            name: 'admin',
            test: (m,c,entry = 'admin') => m.constructor.name === 'CssModule' && issuer(m) === entry,
            chunks: 'all',
            enforce: true,
          }
        }
      }
    },
  };
};

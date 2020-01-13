module.exports = (env, argv, home) => {

  // PLUGINS
  const MiniCssExtractPlugin = require(home + '/node_modules/mini-css-extract-plugin');
  const SourceMapPlugin = require(home + '/node_modules/webpack').SourceMapDevToolPlugin;

  // USER CONFIG
  const config = require(home + '/config/config');

  function issuer(m) {
    return (m.issuer ? issuer(m.issuer) : (m.name ? m.name : false));
  }

  function configSassVariables() {
    let sass = config.styles.sass;

    if (!(typeof sass === 'object' && sass !== null)) return '';

    return Object.keys(sass).reduce((variables, variable) => {
      variables += `$${variable}:${sassifyValue(sass[variable])};`;
      return variables;
    }, '');
  }

  function sassifyValue(value) {
    // map
    if (typeof value === 'object' && value !== null) {
      return `(${Object.keys(value).reduce((_val, _var) => _val += `${_var}:${sassifyValue(value[_var])},`, '')})`;
    }
    // string or number
    return value;
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
                prependData: configSassVariables(),
              }
            },
          ]
        }
      ]
    },

    plugins: [
      new MiniCssExtractPlugin({
        filename: '[name].css'
      }),
      new SourceMapPlugin({
        test: /\.s?css$/
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

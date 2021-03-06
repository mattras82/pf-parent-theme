module.exports = (env, argv) => {
  return {
    externals: { jquery: 'jQuery' },

    module: {
      rules: [
        {
          test: /\.js$/,
          use: 'babel-loader',
          exclude: /node_modules/
        },
      ]
    },
  };
};


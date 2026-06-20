const webpack = require("webpack");
const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  // Define the entry points of our application (can be multiple for different sections of a website)
  entry: {
    "theme-yellow": "./src/theme-yellow.js",
    "theme-black": "./src/theme-black.js",
    "theme-green": "./src/theme-green.js",
    "theme-red": "./src/theme-red.js",
    "theme-rose": "./src/theme-rose.js",
    "theme-violet": "./src/theme-violet.js",
    "theme-white": "./src/theme-white.js",
    "theme-blue": "./src/theme-blue.js",
    "theme-custom": "./src/theme-custom.js"
  },

  // Define the destination directory and filenames of compiled resources
  output: {
    filename: "js/[name].js",
    path: path.resolve(__dirname, "./dist/"),
  },

  // Define development options
  // devtool: "source-map",

  // Define loaders
  module: {
    rules: [
      // Use babel for JS files
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
      // CSS, PostCSS, and Sass
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              importLoaders: 2,
              sourceMap: true,
              url: false,
            },
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: ["autoprefixer"],
              },
            },
          },
          {
            loader: "sass-loader",
            options: {
              // Bootstrap 5.3 + modern Dart Sass emit a flood of deprecation
              // warnings (legacy JS API, @import, global builtins, color/if
              // functions) from node_modules/bootstrap and our @import-based
              // theme files. None are actionable without rewriting Bootstrap,
              // so silence them to keep the build log readable.
              sassOptions: {
                quietDeps: true,
                silenceDeprecations: [
                  "legacy-js-api",
                  "import",
                  "global-builtin",
                  "color-functions",
                  "if-function",
                ],
              },
            },
          },
        ],
      },
    ],
  },

  // Define used plugins
  plugins: [
    // Extracts CSS into separate files
    new MiniCssExtractPlugin({
      filename: "css/[name].css",
      chunkFilename: "[id].css",
    }),
  ],
};

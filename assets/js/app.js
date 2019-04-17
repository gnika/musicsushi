/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/bootstrap.css');
require('../css/global.scss');
require('../css/app.css');
require('../css/fontawesome.min.css');
require('../css/themify-icons.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');
require('bootstrap');
// require('webpack-notifier');
require('../../node_modules/dnl-integration-crud/js/crud.js');
require('../../node_modules/dnl-integration-crud/js/layout.js');


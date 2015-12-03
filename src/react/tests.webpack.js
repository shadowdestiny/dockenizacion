// tests.webpack.js
var context = require.context('./tests', true, /.+\.spec\.jsx?$/);

require('core-js/es5');

context.keys().forEach(context);
module.exports = context;
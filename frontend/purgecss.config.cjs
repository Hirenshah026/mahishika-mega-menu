// Removes the Bootstrap CSS this page does not use.
// Run from the frontend folder after adding new Bootstrap classes:
//   npx purgecss@6.0.0 --config purgecss.config.cjs
module.exports = {
  content: ['index.html', 'js/*.js'],
  css: ['css/vendor/bootstrap.full.min.css'],
  output: 'css/vendor/bootstrap.min.css',
  // Classes and attributes only added by JavaScript
  safelist: ['is-invalid', 'container'],
  dynamicAttributes: ['data-bs-theme', 'aria-invalid'],
  variables: true,
};

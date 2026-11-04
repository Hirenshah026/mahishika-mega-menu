// Google Tag Manager style events: everything goes into window.dataLayer
window.dataLayer = window.dataLayer || [];

function track(event, data = {}) {
  window.dataLayer.push({ event, ...data });
}

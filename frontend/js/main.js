// Laravel backend, used only when the site runs on localhost.
// On the live site (Netlify) the menu comes from data/menu.json. This avoids
// blocked http:// requests and console errors that lower the Lighthouse score.
// After deploying the backend, put its https:// URL here.
const API_URL = location.hostname === 'localhost' ? 'http://localhost:8000/api' : null;

// ---------- Mega menu ----------
async function loadMenu() {
  try {
    if (!API_URL) throw new Error('No API on this host');
    const response = await fetch(`${API_URL}/menu`, { signal: AbortSignal.timeout(3000) });
    if (!response.ok) throw new Error(`API error ${response.status}`);
    return await response.json();
  } catch (error) {
    console.info('Menu API not available, using data/menu.json');
    const response = await fetch('data/menu.json');
    return response.json();
  }
}

loadMenu().then((data) => createMegaMenu(document.getElementById('primary-nav'), data.categories));

// ---------- Dark mode ----------
const themeToggle = document.querySelector('.theme-toggle');

function setTheme(theme) {
  document.documentElement.dataset.theme = theme;
  document.documentElement.dataset.bsTheme = theme;
  themeToggle.setAttribute('aria-pressed', String(theme === 'dark'));
  try { localStorage.setItem('theme', theme); } catch (e) { /* storage blocked */ }
}

// The theme is already applied by the script in <head>. Only sync the button here:
// writing data-theme again would make the browser restyle the whole page for nothing.
themeToggle.setAttribute('aria-pressed', String(document.documentElement.dataset.theme === 'dark'));
themeToggle.addEventListener('click', () => {
  setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
});

// ---------- SweetAlert (loaded only when needed, so the page stays fast) ----------
function loadSwal() {
  if (window.Swal) return Promise.resolve(window.Swal);
  return new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js';
    script.onload = () => resolve(window.Swal);
    script.onerror = reject;
    document.head.appendChild(script);
  });
}

async function showAlert(icon, title, text) {
  try {
    const Swal = await loadSwal();
    Swal.fire({ icon, title, text, confirmButtonColor: '#1270bf' });
  } catch (error) {
    // SweetAlert could not load: the status text under the button is still shown
  }
}

// ---------- Growth audit form ----------
const form = document.getElementById('consultation-form');
const formStatus = document.getElementById('form-status');

// Start loading SweetAlert as soon as the visitor starts filling the form
form.addEventListener('focusin', () => loadSwal().catch(() => {}), { once: true });

// Show one message under each field, e.g. { email: 'Enter a valid email.' }
function showErrors(errors) {
  Object.entries(errors).forEach(([field, message]) => {
    const input = form.elements[field];
    if (!input) return;
    input.classList.add('is-invalid');
    input.setAttribute('aria-invalid', 'true');
    input.nextElementSibling.textContent = message;
  });
  formStatus.textContent = 'Please fix the highlighted fields.';
  form.querySelector('.is-invalid')?.focus();
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();

  // Clear old errors
  form.querySelectorAll('.is-invalid').forEach((input) => {
    input.classList.remove('is-invalid');
    input.removeAttribute('aria-invalid');
  });

  // Browser checks first (required, email format, length), so errors show even without the API
  const invalid = [...form.elements].filter((el) => el.willValidate && !el.checkValidity());
  if (invalid.length) {
    showErrors(Object.fromEntries(invalid.map((el) => [el.name, el.validationMessage])));
    return;
  }

  // The API has one "service" field: send the starting point + the ticked services
  const data = new FormData(form);
  const payload = {
    name: data.get('name'),
    email: data.get('email'),
    message: data.get('message'),
    service: [data.get('start'), ...data.getAll('help')].join(', '),
  };
  formStatus.textContent = 'Sending…';

  try {
    if (!API_URL) throw new Error('No API on this host');

    const response = await fetch(`${API_URL}/consultations`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify(payload),
    });
    const result = await response.json();

    if (response.status === 422) {
      // Laravel validation errors: { errors: { email: ['...'] } }
      showErrors(Object.fromEntries(Object.entries(result.errors).map(([field, messages]) => [field, messages[0]])));
      showAlert('error', 'Please check the form', 'Some fields need your attention.');
      return;
    }

    if (!response.ok) throw new Error(result.message);

    track('consultation_submit', { service: payload.service });
    form.reset();
    formStatus.textContent = result.message;
    showAlert('success', 'Request sent!', result.message);
  } catch (error) {
    formStatus.textContent = 'Sorry, we could not send your request. Please try again later.';
    showAlert('error', 'Something went wrong', formStatus.textContent);
  }
});

// ---------- In-page tabs: highlight the section on screen ----------
const tabLinks = [...document.querySelectorAll('.subnav a')];
const spy = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (!entry.isIntersecting) return;
    tabLinks.forEach((link) => {
      if (link.hash === `#${entry.target.id}`) link.setAttribute('aria-current', 'true');
      else link.removeAttribute('aria-current');
    });
  });
}, { rootMargin: '-40% 0px -55% 0px' });

tabLinks.forEach((link) => spy.observe(document.querySelector(link.hash)));

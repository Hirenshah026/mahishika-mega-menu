// Mega menu: builds the menu from data, then handles click, touch and keyboard.
const isDesktop = window.matchMedia('(min-width: 992px)');

// Escape text coming from the API before putting it into HTML
function esc(text) {
  const div = document.createElement('div');
  div.textContent = text ?? '';
  return div.innerHTML;
}

function renderMenuItem(category) {
  const id = esc(category.slug);

  const links = category.sub_categories.map((sub) => `
    <li>
      <a class="mm-link" href="${esc(sub.url)}">
        <span class="mm-link-title">${esc(sub.name)}</span>
        <span class="mm-link-desc">${esc(sub.description)}</span>
      </a>
    </li>`).join('');

  const cards = category.posts.map((post) => `
    <a class="mm-card" href="${esc(post.url)}">
      ${post.image_url ? `<img src="${esc(post.image_url)}" alt="" width="600" height="400" loading="lazy" decoding="async">` : ''}
      <span class="mm-card-type">${esc(post.type)}</span>
      <span class="mm-card-title">${esc(post.title)}</span>
      <span class="mm-card-text">${esc(post.excerpt)}</span>
    </a>`).join('');

  return `
    <li class="mm-item">
      <button class="mm-trigger" type="button" id="mm-btn-${id}" aria-expanded="false" aria-controls="mm-panel-${id}">
        ${esc(category.name)}
        <svg class="mm-chevron" width="12" height="12" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="2"/></svg>
      </button>
      <div class="mm-panel" id="mm-panel-${id}" hidden>
        <div class="container mm-panel-inner">
          <div class="mm-intro">
            <p class="mm-intro-title">${esc(category.name)}</p>
            <p>${esc(category.description)}</p>
          </div>
          <ul class="mm-links">${links}</ul>
          <div class="mm-featured">${cards}</div>
        </div>
      </div>
    </li>`;
}

function createMegaMenu(nav, categories) {
  const list = nav.querySelector('.mm-list');
  const navToggle = document.querySelector('.nav-toggle');

  list.innerHTML = categories.map(renderMenuItem).join('');
  const triggers = [...list.querySelectorAll('.mm-trigger')];

  const panelOf = (trigger) => document.getElementById(trigger.getAttribute('aria-controls'));
  const openTrigger = () => triggers.find((t) => t.getAttribute('aria-expanded') === 'true');

  function openPanel(trigger) {
    closePanels();
    trigger.setAttribute('aria-expanded', 'true');
    panelOf(trigger).hidden = false;
    track('menu_open', { menu_category: trigger.textContent.trim() });
  }

  function closePanel(trigger) {
    trigger.setAttribute('aria-expanded', 'false');
    panelOf(trigger).hidden = true;
  }

  function closePanels() {
    triggers.forEach(closePanel);
  }

  // Mobile drawer (hamburger)
  function setDrawer(open) {
    nav.classList.toggle('is-open', open);
    navToggle.setAttribute('aria-expanded', String(open));
    navToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    document.body.classList.toggle('nav-open', open);
    if (!open) closePanels();
  }

  function focusTrigger(index) {
    triggers[(index + triggers.length) % triggers.length].focus();
  }

  // Click on a top-level button: open or close its panel
  triggers.forEach((trigger) => {
    trigger.addEventListener('click', () => {
      if (trigger.getAttribute('aria-expanded') === 'true') closePanel(trigger);
      else openPanel(trigger);
    });
  });

  navToggle.addEventListener('click', () => setDrawer(!nav.classList.contains('is-open')));

  // Click on any link inside the menu: track it and close the menu
  nav.addEventListener('click', (event) => {
    const link = event.target.closest('a');
    if (!link) return;
    track('menu_click', { link_text: link.textContent.trim().replace(/\s+/g, ' '), link_url: link.getAttribute('href') });
    setDrawer(false);
  });

  // Click outside the menu closes the open panel
  document.addEventListener('click', (event) => {
    if (!nav.contains(event.target)) closePanels();
  });

  // Desktop: tabbing out of an open panel closes it
  list.addEventListener('focusout', (event) => {
    const item = event.target.closest('.mm-item');
    if (isDesktop.matches && event.relatedTarget && !item.contains(event.relatedTarget)) {
      closePanel(item.querySelector('.mm-trigger'));
    }
  });

  // Keyboard support
  list.addEventListener('keydown', (event) => {
    const trigger = event.target.closest('.mm-trigger');
    const panel = event.target.closest('.mm-panel');
    const nextKey = isDesktop.matches ? 'ArrowRight' : 'ArrowDown';
    const prevKey = isDesktop.matches ? 'ArrowLeft' : 'ArrowUp';

    if (trigger) {
      const index = triggers.indexOf(trigger);
      if (event.key === nextKey) focusTrigger(index + 1);
      else if (event.key === prevKey) focusTrigger(index - 1);
      else if (event.key === 'Home') focusTrigger(0);
      else if (event.key === 'End') focusTrigger(-1);
      else if (event.key === 'ArrowDown' && isDesktop.matches) {
        openPanel(trigger);
        panelOf(trigger).querySelector('a').focus();
      } else return;
      event.preventDefault();
    }

    // Up/Down arrows move between links inside an open panel
    if (panel && (event.key === 'ArrowDown' || event.key === 'ArrowUp')) {
      const links = [...panel.querySelectorAll('a')];
      const index = links.indexOf(event.target);
      const step = event.key === 'ArrowDown' ? 1 : -1;
      links[(index + step + links.length) % links.length].focus();
      event.preventDefault();
    }
  });

  // Escape: close the open panel first, then the mobile drawer
  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    const trigger = openTrigger();
    if (trigger) {
      closePanel(trigger);
      trigger.focus();
    } else if (nav.classList.contains('is-open')) {
      setDrawer(false);
      navToggle.focus();
    }
  });

  // Reset everything when switching between mobile and desktop layouts
  isDesktop.addEventListener('change', () => setDrawer(false));
}

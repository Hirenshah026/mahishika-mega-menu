// Mega menu: builds the Services panel from data, then handles click, hover, touch and keyboard.
const isDesktop = window.matchMedia('(min-width: 992px)');

// Escape text coming from the API before putting it into HTML
function esc(text) {
  const div = document.createElement('div');
  div.textContent = text ?? '';
  return div.innerHTML;
}

const arrow = '<span class="mm-arrow" aria-hidden="true">→</span>';
const dot = (group) => `<span class="mm-dot" style="--dot: ${esc(group.color)}" aria-hidden="true"></span>`;

// Left column: one tab per group (Design, Video, Tech, Marketing)
function renderTab(group, index) {
  const id = esc(group.slug);
  return `
    <li role="presentation">
      <button class="mm-tab" type="button" role="tab" id="mm-tab-${id}" aria-controls="mm-group-${id}"
              aria-selected="${index === 0}" tabindex="${index === 0 ? 0 : -1}">
        ${dot(group)}
        <span class="mm-tab-text">
          <span class="mm-tab-name">${esc(group.name)}</span>
          <span class="mm-tab-count">${group.sub_categories.length} services</span>
        </span>
        <svg class="mm-tab-chevron" width="8" height="12" viewBox="0 0 8 12" aria-hidden="true"><path d="M2 2l4 4-4 4" fill="none" stroke="currentColor" stroke-width="2"/></svg>
      </button>
    </li>`;
}

// Middle + right columns: the group's links and its featured project
function renderGroup(group) {
  const id = esc(group.slug);
  const post = group.posts[0];

  const links = group.sub_categories.map((sub) => `
    <li>
      <a class="mm-link" href="${esc(sub.url)}">
        <span class="mm-link-title">${esc(sub.name)}</span>
        <span class="mm-link-desc">${esc(sub.description)}</span>
        ${arrow}
      </a>
    </li>`).join('');

  const featured = post ? `
    <div class="mm-featured">
      <p class="mm-eyebrow">Featured project</p>
      <a class="mm-card" href="${esc(post.url)}">
        ${post.image_url ? `<img src="${esc(post.image_url)}" alt="" width="432" height="260" loading="lazy" decoding="async">` : ''}
        <span class="mm-card-title">${esc(post.title)}</span>
        <span class="mm-card-link">View in portfolio ${arrow}</span>
      </a>
    </div>` : '';

  return `
    <div class="mm-group" role="tabpanel" id="mm-group-${id}" aria-labelledby="mm-tab-${id}">
      <div class="mm-group-main">
        <div class="mm-group-head">
          <p class="mm-group-title">${dot(group)}${esc(group.name)} Services</p>
          <a class="mm-hub" href="${esc(group.url)}">Explore ${esc(group.name)} hub ${arrow}</a>
        </div>
        <p class="mm-group-desc">${esc(group.description)}</p>
        <ul class="mm-links">${links}</ul>
      </div>
      ${featured}
    </div>`;
}

function renderPanel(groups) {
  return `
    <div class="mm-body">
      <div class="mm-groups">
        <p class="mm-eyebrow" id="mm-groups-label">Services</p>
        <ul class="mm-tabs" role="tablist" aria-labelledby="mm-groups-label" aria-orientation="vertical">
          ${groups.map(renderTab).join('')}
        </ul>
      </div>
      ${groups.map(renderGroup).join('')}
    </div>
    <div class="mm-footer">
      <p><strong>Not sure where to start?</strong> We'll map it with you.</p>
      <div class="mm-footer-links">
        <a class="mm-footer-cta" href="#consultation">Get a free growth audit ${arrow}</a>
        <a href="#services">All services ${arrow}</a>
      </div>
    </div>`;
}

function createMegaMenu(nav, groups) {
  const trigger = nav.querySelector('.mm-trigger');
  const panel = document.getElementById(trigger.getAttribute('aria-controls'));
  const item = trigger.closest('li');
  const backdrop = document.querySelector('.mm-backdrop');
  const navToggle = document.querySelector('.nav-toggle');

  panel.innerHTML = renderPanel(groups);
  const tabs = [...panel.querySelectorAll('.mm-tab')];
  const groupPanels = [...panel.querySelectorAll('.mm-group')];
  const topItems = [...nav.querySelectorAll('.mm-top')];
  let active = 0;

  const isOpen = () => trigger.getAttribute('aria-expanded') === 'true';

  function openPanel() {
    trigger.setAttribute('aria-expanded', 'true');
    panel.hidden = false;
    backdrop.hidden = !isDesktop.matches;
    track('menu_open', { menu_category: 'Services' });
  }

  function closePanel() {
    trigger.setAttribute('aria-expanded', 'false');
    panel.hidden = true;
    backdrop.hidden = true;
  }

  // Desktop shows one group at a time (tabs). Mobile lists every group.
  function selectGroup(index) {
    active = index;
    tabs.forEach((tab, i) => {
      tab.setAttribute('aria-selected', String(i === index));
      tab.tabIndex = i === index ? 0 : -1;
    });
    groupPanels.forEach((group, i) => {
      group.hidden = isDesktop.matches && i !== index;
    });
  }

  // Mobile drawer (hamburger)
  function setDrawer(open) {
    nav.classList.toggle('is-open', open);
    navToggle.setAttribute('aria-expanded', String(open));
    navToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    document.body.classList.toggle('nav-open', open);
    if (!open) closePanel();
  }

  const wrap = (list, index) => list[(index + list.length) % list.length];
  const linksIn = (el) => [...el.querySelectorAll('a')];

  trigger.addEventListener('click', () => (isOpen() ? closePanel() : openPanel()));
  navToggle.addEventListener('click', () => setDrawer(!nav.classList.contains('is-open')));

  // Click or hover a group to show its links
  tabs.forEach((tab, i) => {
    tab.addEventListener('click', () => selectGroup(i));
    tab.addEventListener('mouseenter', () => { if (isDesktop.matches) selectGroup(i); });
  });

  // Click on any link inside the menu: track it and close the menu
  nav.addEventListener('click', (event) => {
    const link = event.target.closest('a');
    if (!link) return;
    track('menu_click', { link_text: link.textContent.trim().replace(/\s+/g, ' '), link_url: link.getAttribute('href') });
    setDrawer(false);
  });

  // Click outside the menu (including the dimmed backdrop) closes the panel
  document.addEventListener('click', (event) => {
    if (!nav.contains(event.target)) closePanel();
  });

  // Desktop: tabbing out of the open panel closes it
  item.addEventListener('focusout', (event) => {
    if (isDesktop.matches && event.relatedTarget && !item.contains(event.relatedTarget)) closePanel();
  });

  // Keyboard support
  nav.addEventListener('keydown', (event) => {
    const { key, target } = event;
    const nextKey = isDesktop.matches ? 'ArrowRight' : 'ArrowDown';
    const prevKey = isDesktop.matches ? 'ArrowLeft' : 'ArrowUp';

    // Top-level items: Services, Portfolio, Industries...
    if (target.classList.contains('mm-top')) {
      const index = topItems.indexOf(target);
      if (key === nextKey) wrap(topItems, index + 1).focus();
      else if (key === prevKey) wrap(topItems, index - 1).focus();
      else if (key === 'Home') topItems[0].focus();
      else if (key === 'End') topItems[topItems.length - 1].focus();
      else if (key === 'ArrowDown' && target === trigger && isDesktop.matches) {
        openPanel();
        tabs[active].focus();
      } else return;
      event.preventDefault();
      return;
    }

    // Group tabs: Up/Down switch group, Right jumps into its links
    if (target.classList.contains('mm-tab')) {
      const index = tabs.indexOf(target);
      if (key === 'ArrowDown') selectGroup((index + 1) % tabs.length);
      else if (key === 'ArrowUp') selectGroup((index - 1 + tabs.length) % tabs.length);
      else if (key === 'Home') selectGroup(0);
      else if (key === 'End') selectGroup(tabs.length - 1);
      else if (key === 'ArrowRight') {
        linksIn(groupPanels[active])[0].focus();
        event.preventDefault();
        return;
      } else return;
      tabs[active].focus();
      event.preventDefault();
      return;
    }

    // Links inside a group or the footer: Up/Down move between them, Left goes back to the tabs
    const box = target.closest('.mm-group, .mm-footer');
    if (!box) return;
    const links = linksIn(box);
    const index = links.indexOf(target);
    if (key === 'ArrowDown') wrap(links, index + 1).focus();
    else if (key === 'ArrowUp') wrap(links, index - 1).focus();
    else if (key === 'ArrowLeft' && isDesktop.matches && box.classList.contains('mm-group')) tabs[active].focus();
    else return;
    event.preventDefault();
  });

  // Escape: close the panel first, then the mobile drawer
  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    if (isOpen()) {
      closePanel();
      trigger.focus();
    } else if (nav.classList.contains('is-open')) {
      setDrawer(false);
      navToggle.focus();
    }
  });

  // Reset when switching between mobile and desktop layouts
  isDesktop.addEventListener('change', () => {
    setDrawer(false);
    selectGroup(active);
  });

  selectGroup(0);
}

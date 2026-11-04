# Mahishika Digital – Mega Menu + Services Page

A responsive, accessible mega menu used on a single Services landing page, with a small
Laravel backend to manage the menu (categories, sub-categories, portfolio/articles).

## Setup and run

### 1. Frontend (static)
1. Copy the project into `C:\xampp\htdocs\mahishikadigital` and start **Apache** in XAMPP.
2. Open http://localhost/mahishikadigital/frontend/

The frontend works on its own. If the backend is not running, the menu loads from
`frontend/data/menu.json`.

### 2. Backend (Laravel + MySQL)
1. Start **MySQL** in XAMPP.
2. Open phpMyAdmin (http://localhost/phpmyadmin) and create a database named `mahishika_digital`.
3. In a terminal:
   ```bash
   cd backend
   composer install
   copy .env.example .env      # only needed on a fresh clone
   php artisan key:generate    # only needed on a fresh clone
   php artisan migrate --seed
   php artisan storage:link    # makes uploaded images public
   php artisan serve
   ```
4. Admin panel: http://localhost:8000/admin/login
   - Email: `admin@mahishika.com`
   - Password: `Admin@123`
5. Menu API: http://localhost:8000/api/menu
6. Tests: `php artisan test`

Reload the frontend. The menu now comes from the database. Change something in the admin panel and reload to see it.

## Tech choices
- **HTML/CSS/JS + Bootstrap 5 (CSS only)**: Bootstrap gives a tested grid, spacing utilities and form styles,
  so the page is responsive with little custom CSS. Its navbar/dropdown JS is **not** used: the mega
  menu is written from scratch, as the brief asks.
- **Vanilla JavaScript**: the menu is about 150 lines with no build step, so it loads fast.
- **Laravel 13 + MySQL**: simple CRUD with built-in validation, JSON error responses and CORS.

## Folder overview
```
frontend/
  index.html          Page markup (header, sections, form)
  css/tokens.css      Design tokens: colors, spacing, typography (+ dark theme)
  css/base.css        Global styles, buttons, focus ring
  css/megamenu.css    Mega menu (desktop panels + mobile drawer)
  css/page.css        Landing page sections
  js/megamenu.js      Builds the menu from data + all interactions
  js/main.js          Loads menu data, dark mode, consultation form
  js/analytics.js     dataLayer helper (GTM events)
  data/menu.json      Fallback menu data
backend/
  app/Models                        Category, SubCategory, Post, Consultation
  app/Http/Controllers/Api          MenuController (GET /api/menu), ConsultationController (POST)
  app/Http/Controllers/Admin        Login/logout + CRUD for categories, sub-categories, posts (with image upload)
  resources/views/admin             Blade admin screens
  database/migrations, seeders      Tables + seed data (database/data/menu.json)
```

**Data model:** Category (top menu item) → Sub-categories (links in the panel) and
Posts (portfolio/article cards featured in the panel).

## Accessibility decisions
- `<nav aria-label="Main">`, `<ul>` lists and real `<button>`s for top-level items.
- Disclosure pattern: each button has `aria-expanded` and `aria-controls` pointing to its panel.
  `aria-haspopup` is not used because the panels hold normal links, not an ARIA `menu`.
- Keyboard:
  - Tab / Shift+Tab move through items as usual.
  - Enter / Space open or close a panel.
  - Arrow Left/Right (desktop) or Up/Down (mobile) move between top-level items. Home/End jump to the first or last item.
  - Arrow Down on a desktop item opens the panel and focuses its first link. Up/Down move between links.
  - Esc closes the panel and returns focus to its button. A second Esc closes the mobile drawer.
- Focus leaving a panel closes it. A click outside closes it too.
- Visible focus ring (3px, contrast ≥ 3:1), a skip link, and 44px touch targets.
- Text colors pass WCAG AA in light and dark themes. `prefers-reduced-motion` turns off animations.
- Panels open on **click, not hover**. This works the same with mouse, touch and keyboard, and avoids accidental opens.

## Performance
**Lighthouse: 100 / 100 / 100 / 100** (Performance, Accessibility, Best Practices, SEO) on mobile and desktop.
Full reports: [docs/lighthouse/](docs/lighthouse/).

- Bootstrap is self-hosted and purged to the classes this page uses (233 KB → 12 KB) with PurgeCSS.
  After adding new Bootstrap classes, run `npx purgecss@6.0.0 --config purgecss.config.cjs` in `frontend/`.
- CSS/JS have `?v=` version tags and are cached for a year (`netlify.toml`, `.htaccess`).
  Bump the number after editing a file.
- The Laravel API is only called on `localhost`. The live site reads `data/menu.json`, so there are no blocked
  `http://` requests or console errors.
- No framework JS and no web fonts (system font stack).
- Images have `width`/`height` (no layout shift) and `loading="lazy"`.
- The header has a fixed height, so the menu loading late does not move the page.

## SEO
- Keyword-focused `<title>` and meta description, one `<h1>`, then `<h2>`/`<h3>` in order.
- Canonical URL, Open Graph + Twitter card tags with a 1200×630 share image (`images/og-image.png`).
- JSON-LD structured data (`ProfessionalService` with its list of services).
- `robots.txt` + `sitemap.xml` in `frontend/`. The backend's `robots.txt` blocks everything, and the admin pages use `noindex`.
- The footer repeats the menu links in plain HTML, so crawlers can follow them without running JS.
- **Before deploying:** replace `https://mahishika-digital.netlify.app/` with your real URL in `index.html`, `robots.txt` and `sitemap.xml`.

## Alerts
SweetAlert2 handles delete and logout confirmations, success toasts and validation pop-ups in the admin.
On the public page it shows the form result. It loads only when someone starts filling in the form, so it doesn't slow the first page load.

## Bonus features
- Dark mode toggle (remembers the choice, respects the system setting).
- Analytics: `menu_open`, `menu_click` and `consultation_submit` events pushed to `window.dataLayer`.

## Responsive
Tested layouts at 320, 375, 768, 1024, 1280 and 1536px. Below 992px the menu becomes a drawer
with accordion panels.

## Assumptions
- I had no Zeplin access, so the visual design is my own. It uses the same structure: intro, links and featured cards.
- The backend URL is set in `frontend/js/main.js` (`API_URL`).

## Known limitations / next steps
- There is one admin account (seeded) and no "forgot password" or user management.
- The mobile drawer does not trap focus yet.
- Uploaded images are not resized or converted to WebP.
- The menu needs JavaScript. A server-rendered fallback would help without JS.
- Next: search inside the mega menu, automated tests (PHPUnit for API, Playwright for keyboard).

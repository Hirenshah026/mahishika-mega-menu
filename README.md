# Mahishika Digital – Mega Menu + Services Page

A responsive, accessible mega menu used on a single Services landing page, with a small
Laravel backend to manage the menu (categories, sub-categories, portfolio/articles).

- **Live site:** https://mahishikamegamenu.netlify.app/
- **Repository:** https://github.com/Hirenshah026/mahishika-mega-menu

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

**Data model:** Category = a service group in the mega menu (Design, Video, Tech, Marketing; with a dot color
and an "Explore … hub" link) → Sub-categories (the group's service links) and Posts (the "Featured project" card).

## Mega menu layout (matches the Zeplin design)
Top menu: **Services** (opens the mega menu), Portfolio, Industries, About, Blog, Contact + "Book a growth audit".
The Services panel has 3 columns: service groups on the left, the selected group's links in the middle,
and a featured project on the right, plus a footer bar. The page behind is dimmed while it is open.
On screens below 992px the groups are listed one after another inside the drawer.

## Accessibility decisions
- `<nav aria-label="Main">`, `<ul>` lists and a real `<button>` for Services.
- Disclosure pattern: the Services button has `aria-expanded` and `aria-controls` pointing to its panel.
  `aria-haspopup` is not used because the panel holds normal links, not an ARIA `menu`.
- The group list on the left is an ARIA **tabs** pattern (`role="tablist"`, `aria-selected`, roving `tabindex`),
  because choosing a group swaps the content next to it.
- Keyboard:
  - Tab / Shift+Tab move through items as usual.
  - Enter / Space open or close the panel.
  - Arrow Left/Right (desktop) or Up/Down (mobile) move between top-level items. Home/End jump to the first or last item.
  - Arrow Down on Services (desktop) opens the panel and focuses the selected group.
  - In the group list, Up/Down switch groups and Right jumps into that group's links.
  - In the links, Up/Down move between links and Left goes back to the group list.
  - Esc closes the panel and returns focus to Services. A second Esc closes the mobile drawer.
- Focus leaving the panel closes it. A click outside (or on the dimmed page) closes it too.
- Visible focus ring (3px, contrast ≥ 3:1), a skip link, and 44px touch targets.
- Text colors pass WCAG AA in light and dark themes. `prefers-reduced-motion` turns off animations.
- The panel opens on **click, not hover**. This works the same with mouse, touch and keyboard, and avoids accidental opens.
  Inside the open panel, hovering a group switches to it, as in the design.

## Performance
**Lighthouse: 100 / 100 / 100 / 100** (Performance, Accessibility, Best Practices, SEO) on mobile and desktop.
Full reports: [docs/lighthouse/](docs/lighthouse/).

- Bootstrap is self-hosted and purged to the classes this page uses (233 KB → 12 KB) with PurgeCSS.
  After adding new Bootstrap classes, run `npx purgecss@6.0.0 --config purgecss.config.cjs` in `frontend/`.
- CSS/JS have `?v=` version tags and are cached for a year (`netlify.toml`, `.htaccess`).
  Bump the number after editing a file.
- The Laravel API is only called on `localhost`. The live site reads `data/menu.json`, so there are no blocked
  `http://` requests or console errors.
- No framework JS. Poppins (the design's font) is self-hosted: five 8 KB files, the two main weights preloaded,
  and a size-matched fallback font so text doesn't shift when Poppins loads.
- Images have `width`/`height` (no layout shift) and `loading="lazy"`.
- The header has a fixed height, so the menu loading late does not move the page.

## SEO
- Keyword-focused `<title>` and meta description, one `<h1>`, then `<h2>`/`<h3>` in order.
- Canonical URL, Open Graph + Twitter card tags with a 1200×630 share image (`images/og-image.png`).
- JSON-LD structured data (`ProfessionalService` with its list of services).
- `robots.txt` + `sitemap.xml` in `frontend/`. The backend's `robots.txt` blocks everything, and the admin pages use `noindex`.
- The footer repeats the menu links in plain HTML, so crawlers can follow them without running JS.
- Live URL: https://mahishikamegamenu.netlify.app/. If the domain changes, update it in `index.html`, `robots.txt` and `sitemap.xml`.

## Alerts
SweetAlert2 handles delete and logout confirmations, success toasts and validation pop-ups in the admin.
On the public page it shows the form result. It loads only when someone starts filling in the form, so it doesn't slow the first page load.

## Bonus features
- Dark mode toggle (remembers the choice, respects the system setting).
- Analytics: `menu_open`, `menu_click` and `consultation_submit` events pushed to `window.dataLayer`.

## Responsive
Tested layouts at 320, 375, 768, 1024, 1280 and 1536px. Below 992px the menu becomes a drawer
where Services expands to show every group.

## Assumptions
- The header and mega menu follow the Zeplin design (colors, Poppins, layout). The top menu items follow the design
  rather than the PDF brief (which listed Services, Industries, Work, Approach, About).
- Zeplin had no mobile design, so the mobile layout is my own: menu groups are stacked, the featured project is hidden,
  and the audit form sits under the hero.
- The page is the Zeplin "Analytics & Tracking" service page. The top of the hero was hidden under the open menu in the
  design export, so the breadcrumb, headline and intro are my own, styled to match.
- The design's placeholders ("[Client name]", "₹[00L]") are filled with sample content. The testimonial keeps the
  design's "Sample copy" label, and the case studies are labelled as samples, so nothing reads as a real client claim.
- The design's reviewer notes (the top "page draft" bar, "Link each chip to its industry page…") are not shown.
- Portfolio, Industries, About, Blog and Contact would be separate pages on the real site. Here they jump to the
  matching section (Blog → "More from Marketing", About/Contact → footer).
- The form combines "Where should we start?" and the ticked services into the API's `service` field, so the backend
  did not need to change.
- The backend URL is set in `frontend/js/main.js` (`API_URL`).

## Known limitations / next steps
- There is one admin account (seeded) and no "forgot password" or user management.
- The mobile drawer does not trap focus yet.
- Uploaded images are not resized or converted to WebP.
- The menu needs JavaScript. A server-rendered fallback would help without JS.
- Next: search inside the mega menu, automated tests (PHPUnit for API, Playwright for keyboard).

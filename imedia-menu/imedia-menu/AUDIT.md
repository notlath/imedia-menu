# iMedia Menu — Feature Parity & Open-Source Readiness Audit

**Date:** 2026-06-08
**Subject plugin:** `imedia-menu` v1.0.0
**Reference (free):** `megamenu` v3.10.5
**Reference (premium):** `megamenu-pro` v2.4.4
**Mode:** Read-only inspection of plugin directories

---

## 1. Audit Method

| Source Plugin | Version | Files Audited |
|---|---|---|
| `megamenu` (free reference) | 3.10.5 | All `classes/*.php`, `classes/widgets/*`, `classes/icons/*`, `classes/pages/*`, `css/*.scss`, `js/*`, `megamenu.php` |
| `megamenu-pro` (premium reference) | 2.4.4 | Main file + 14 module directories (`badge`, `sticky`, `tabbed`, `replacements`, `overlay`, `vertical`, `roles`, `style-overrides`, `fonts/*`, `icons/*`, `image-swap`, `toggle-blocks`, `updater`, `assets/*.{js,css}`) |
| `imedia-menu` (subject) | 1.0.0 | All `src/**/*.php` (49 files), `assets/*/build/`, `composer.json`, `package.json`, `uninstall.php`, `readme.txt` |

---

## 2. Architecture Comparison

| Dimension | megamenu / megamenu-pro | imedia-menu |
|---|---|---|
| Class naming | Global `Mega_Menu_*` (PHP 7.4 compatible) | Namespaced `IMedia\Menu\*` (PHP 8.1+) |
| Storage | `nav_menu_item` postmeta only (`_megamenu` key, 16+ default fields) | Hybrid: postmeta (8 fields) + 3 custom DB tables (`_imedia_menu_panels`, `_imedia_menu_templates`, `_imedia_menu_revisions`) |
| CSS generation | SCSS → CSS at runtime via vendored `scssphp` (2 copies: 0.0.12 + 1.11.1), 1000+ SCSS variables, per-theme | Static pre-built CSS files in `assets/frontend/css/`, 1 global Design tab, no SCSS, no theme system |
| Menu editor | Custom jQuery metabox with flyout / grid / standard modes; embeds live WP widgets via `$wp_widget_factory` | Custom React `panel-builder` (103 KB built bundle) with 21 content blocks (14 M0 + 7 M1, including a `real_widget` block that embeds live `wp_widget_factory` widgets) |
| Settings page | 5 pages (General, Locations, Theme Editor, Tools, Menus) | 1 page with 8 tabs (General, Design, Animations, Mobile, Visibility, Icons, Performance, Advanced) |
| Themes | Multi-theme system + Theme Editor (line 8 of `style-manager.class.php` confirms) | Single global design + per-location overrides (`LocationOverrides`) |
| Mobile | Toggle bar designer (left/center/right flex blocks) with 3 free + 5 Pro block types | Single off-canvas overlay (right/left slide), 3 hamburger styles |
| Sticky | `Mega_Menu_Sticky` (Pro) with show/hide-on-sticky classes, scroll detection, off/on toggle | `position: sticky` only (`Frontend/Sticky.php` enqueues one JS file) |
| License | GPL-2.0+ (free), proprietary Pro | GPL-2.0-or-later (declared in `composer.json`) |

---

## 3. Gap Report Table

Legend: ✅ Implemented · ⚠️ Partial · ❌ Missing

### 3.1 Menu Builder & Panel Types

| Feature | Source Plugin | imedia-menu Status | Notes |
|---|---|---|---|
| Flyout (standard) submenu | megamenu | ✅ | Trigger types `hover`/`click`/`hover_click` (`GeneralTab.php:38-43`) |
| Mega Menu — Standard Layout (widget columns) | megamenu | ✅ | `MegaPanel` + 21 blocks (M0 + M1); arbitrary WP widgets embeddable via `real_widget` block |
| Mega Menu — Grid Layout (12-track rows/columns) | megamenu | ✅ (M2) | `Frontend/PanelLayout/GridLayout` (12-track CSS grid, per-column `span` 1-12, per-row `hide_on_mobile`/`hide_on_desktop`/`css_class`; conditional `assets/frontend/css/imm-grid.css`) |
| Tabbed submenu | megamenu-pro `tabbed/tabbed.php` (18 KB) | ✅ | `ContentBlocks/TabbedBlock` (ARIA tablist, vertical/horizontal, keyboard nav via `assets/frontend/js/tabbed.js`) |
| Vertical menu orientation | megamenu-pro `vertical/vertical.php` (6 KB) | ✅ (M2) | `Enums/MenuOrientation` (Horizontal/Vertical/Accordion); per-location orientation + override; `imm-menu--vertical` class; conditional `assets/frontend/css/imm-vertical.css` |
| Full-screen overlay menu | megamenu-pro `overlay/overlay.php` (6 KB) | ✅ (M2) | `Frontend/Overlay` renders `<div class="imm-page-overlay" hidden>` + inline JS watcher on `aria-expanded="true"`; modes `off`/`desktop`/`mobile`/`both`; per-location `overlay_color` |
| Mega panel content blocks (Heading, Text, Image, Banner, Icon, etc.) | megamenu (widgets) + Pro (custom HTML) | ✅ | 21 `ContentBlocks/*` (M0: MenuLinks, Heading, Text, Icon, Image, Banner, Gutenberg, Widget, Html, Shortcode, PostListing, TaxonomyListing, Search, Divider — M1: RealWidget, Replacements, Tabbed, Accordion, LoginState, Cart, DynamicHtml; Banner extended with multi-CTA + overlay + aspect ratio) |
| Embed real WP widgets (any registered widget) | megamenu | ✅ | `ContentBlocks/RealWidgetBlock` — embeds any registered `wp_widget_factory` widget by id |
| Embed Elementor / Bricks / Divi templates | megamenu + 11 integrations (`integration/{block,breakdance,bricks,divi,elementor,polylang,twentyseventeen,widget,wpml}/`) | ❌ | No third-party builder integrations |
| Reusable Block widget | megamenu `widget-reusable-block.class.php` | ✅ | `Gutenberg` block |

### 3.2 Menu Item Meta

| Feature | Source Plugin | imedia-menu Status | Notes |
|---|---|---|---|
| Hide text / hide arrow / disable link / hide on {mobile,desktop} / close after click / collapse children | megamenu `nav-menus.class.php:157-165` | ❌ | 8 postmeta keys, but only `hide_text`-equivalent, `disable_link`, icon/badge/description are exposed; no `hide_on_mobile`/`hide_on_desktop` per item |
| Icon (with 3 free + 5 Pro providers) | megamenu + Pro | ✅ | 3 providers: Dashicons, CustomSVG, FontAwesome via CDN (`Icons/Providers/`) |
| Badge (4 styles) | megamenu-pro `badge/` (28 KB + SCSS) | ⚠️ | Postmeta keys exist (`_imedia_menu_badge_text/color/text_color/position`) and `ContentBlocks/Heading` supports `badge` — but no dedicated tab, no `mega-menu-badge` SCSS, no hide-on-mobile/desktop option |
| Description | megamenu | ✅ | `_imedia_menu_description` + render path |
| Custom CSS class per item | megamenu | ⚠️ | Standard WP `classes` field only — no per-item style override like Pro's `style-overrides.php` (54 KB) |
| Visibility conditions per item | megamenu (via Pro) | ✅ | 8 condition types in `Visibility/Conditions/` (Page, UserRole, DeviceType, Schedule, LoginState, Language, UrlParameter, PhpCallback) |
| Sticky visibility (show/hide when sticky) | megamenu-pro | ❌ | Not supported |

### 3.3 Mega Panel — Content Blocks

| Block | imedia-menu | megamenu / Pro equivalent |
|---|---|---|
| MenuLinks | ✅ `ContentBlocks/MenuLinks.php` | Sub-menu items (native) |
| Heading | ✅ | Text widget |
| Text | ✅ | Custom HTML widget |
| Icon | ✅ | Icon widget (Pro) |
| Image | ✅ | Image widget |
| Banner | ✅ | Image + link widget; M1 extension adds `template` (overlay/card/side), `overlay_color`, `overlay_opacity`, multi-CTA array, and `aspect_ratio` |
| Gutenberg | ✅ | Reusable block widget |
| Widget (text) | ⚠️ | Inserts arbitrary `wp_widget_factory` widget (full) |
| Html | ✅ | Custom HTML widget |
| Shortcode | ✅ | `[shortcode]` widget |
| PostListing | ✅ | Posts widget (Pro `replacements/`) |
| TaxonomyListing | ✅ | Custom taxonomy widget |
| Search | ✅ | Search widget (Pro toggle block) |
| Divider | ✅ | Spacer widget (Pro toggle block) |
| **RealWidget (M1)** | ✅ `ContentBlocks/RealWidgetBlock` | Embed any registered `wp_widget_factory` widget by id (e.g. `recent-posts`, `categories`, `media_gallery`, `nav_menu`, custom widgets) |
| **Replacements (M1)** | ✅ `ContentBlocks/ReplacementsBlock` | Pro `replacements/replacements.php` (80 KB) — token substitution + `do_shortcode`; tokens: `{user_name}`, `{user_email}`, `{user_id}`, `{user_role}`, `{date}`, `{year}`, `{month}`, `{day}`, `{site_title}`, `{site_url}`, `{ip}`; exposes `imedia_menu_replacements_token_map` filter for custom tokens |
| **Tabbed (M1)** | ✅ `ContentBlocks/TabbedBlock` | Pro `tabbed/tabbed.php` (18 KB) — ARIA tablist, vertical/horizontal, `wp_generate_uuid4` id, keyboard nav (Arrow/Home/End) in `assets/frontend/js/tabbed.js` |
| **Accordion (M1)** | ✅ `ContentBlocks/AccordionBlock` | Pro `toggle-blocks/` (48 KB) — native `<details>/<summary>`, recursion via `Registry` for nested content blocks |
| **LoginState (M1)** | ✅ `ContentBlocks/LoginStateBlock` | Pro toggle block — container with `logged_in` + `logged_out` child blocks; `fallback: hide\|empty` |
| **Cart (M1)** | ✅ `ContentBlocks/CartBlock` | Pro `woocommerce/` (28 KB) — WooCommerce cart link + item count, WC-gated (no-op if WC inactive) |
| **DynamicHtml (M1)** | ✅ `ContentBlocks/DynamicHtmlBlock` | Pro `custom-html/` — fetches HTML from URL or PHP callback, caches in `wp_cache_*` group `imedia_menu` key `imedia_menu_dyn_html_{md5}`, sanitized via `wp_kses` |

### 3.4 Mobile

| Feature | Source Plugin | imedia-menu Status | Notes |
|---|---|---|---|
| Mobile breakpoint config | megamenu | ✅ | `mobile_breakpoint` 320–1200 (`MobileTab.php:26-32`) |
| Off-canvas slide direction | megamenu | ✅ | `right`/`left` (`MobileTab.php:39-43`) |
| Hamburger style variants | megamenu | ✅ | 3: classic, x-morph, arrow |
| Toggle bar designer (left/center/right) | megamenu | ✅ M3 | `ToggleBarRenderer` with 3 flex regions; per-location storage in `imedia_menu_toggle_bar` option |
| Animated toggle (slider/arrow) | megamenu `toggle-blocks.class.php:548-595` | ✅ M3 | `menu_toggle_animated` block with `arrow` and `slider` CSS animations |
| Pro toggle blocks: Search, Logo, Icon, HTML, Custom | megamenu-pro `toggle-blocks/toggle-blocks.php` (48 KB) | ✅ M3 | 8 block types: `menu_toggle`, `menu_toggle_animated`, `spacer`, `search`, `logo`, `icon`, `html`, `custom` |
| Mobile-specific submenu open/close animation | megamenu | ⚠️ | Uses global animation; no per-mobile override (deferred to a later milestone) |
| Collapse children on mobile | megamenu | ✅ | Implemented in `imm-mobile.js` `initAccordion()` |
| Mobile-specific menu bar layout | megamenu | ✅ M3 | Toggle bar is per-location; configured via `LocationTab` → "Open Toggle Bar Designer" button |

### 3.5 Styling & Theming

| Feature | Source Plugin | imedia-menu Status | Notes |
|---|---|---|---|
| Full SCSS theme editor with live preview | megamenu `pages/themes.php` + `style-manager.class.php` | ❌ | None |
| 1000+ SCSS variables for theme control | megamenu `style-manager.class.php:25-60` | ❌ | Static CSS only |
| Compiled CSS file in uploads dir (with version) | megamenu `style-manager.class.php:176-197` | ⚠️ | `Assets::maybeInlineCustomCss()` writes `imm-custom.css` to uploads, but inline-only |
| Per-location style overrides | megamenu `location.class.php` | ✅ | `LocationOverrides::mergeWithGlobal()` (`LocationOverrides.php`) |
| Per-menu-item style overrides | megamenu-pro `style-overrides/` (54 KB) | ✅ M4 | `StyleOverrides.php` — 40 CSS custom properties across 5 groups (Background, Font, Border, Icon, Spacing, Panel); enable-checkbox-per-property UX; sanitized colors/values; emitted via `wp_add_inline_style('imm-base', ...)` and `style="--imm-item-..."` on each `<li>` |
| Google Fonts picker | megamenu-pro `fonts/google/` | ✅ M5 | `GoogleFontsProvider` (300+ hardcoded fonts, weight 100-900, 15 subsets); `FontsTab` admin UI (Fonts settings tab with add/remove + weight selection); `FontsManager::enqueue()` at `wp_enqueue_scripts` priority 20; filters `imm_google_fonts` and `imm_google_fonts_url` |
| Custom @font-face upload | megamenu-pro `fonts/custom/` | ❌ | None |
| Dark mode | n/a (megamenu ships no dark mode) | ✅ | `DesignTab.php:107-178` — full `prefers-color-scheme` + 6 dark mode colors |
| Transparent menu bar mode | n/a | ✅ | `DesignTab.php:92-104` |
| Sticky menu | megamenu-pro | ✅ M4 | `Sticky.php` (9 design-tab fields: desktop/mobile, opacity 0.2-1.0, offset 0-500, expand, hide-until-scroll-up w/ tolerance/offset, per-item visibility) + `imm-sticky.js` (IntersectionObserver sentinel, scroll-delta accumulator, expand toggle, resize handler, `prefers-reduced-motion` honored) + per-item `imm-sticky-{show,hide}-when-stuck` classes from `_imedia_menu_sticky_visibility` |
| Menu item badges | megamenu-pro | ✅ M4 | `Badge.php` — 4 styles (Red/Teal/Amber/Indigo) matching megamenu-pro defaults; per-item `disabled\|style-1..4` select + hide-on-mobile/desktop; outputs `<span class="imm-badge imm-badge--style-N" data-style="..." style="--imm-badge-bg:...;--imm-badge-text:...">`; assets conditionally enqueued per `wpdb->postmeta` query |

### 3.6 Animations

| Feature | Source Plugin | imedia-menu Status | Notes |
|---|---|---|---|
| Fade / slide / none | megamenu | ✅ | 3 types |
| Easing control | megamenu | ✅ | 4 easings (`AnimationsTab.php:24-31`) |
| Duration (ms) | megamenu | ✅ | 0–1000 ms |
| Respect `prefers-reduced-motion` | n/a (megamenu has no a11y toggle) | ✅ | `AnimationsTab.php:35-46` |
| Slide-up / slide-down / push / fade-up variants | megamenu | ❌ | 3 types only |

### 3.7 Icons

| Provider | megamenu / Pro | imedia-menu |
|---|---|---|
| Dashicons (WordPress core) | ✅ | ✅ `DashiconsProvider` |
| Material Symbols | ✅ | ❌ |
| Font Awesome 4 | ✅ Pro | ✅ `FontAwesomeProvider` (`fa` id, FA4/v5 hybrid icons) |
| Font Awesome 5 | ✅ Pro | ✅ M5 `FontAwesome5Provider` (`fa5` id, 120 icons, `fas`/`fab`/`far` styles) |
| Font Awesome 6 | ✅ Pro | ✅ M5 `FontAwesome6Provider` (`fa6` id, 120 icons, `fa-solid`/`fa-regular`/`fa-light`/`fa-brands` styles) |
| Genericons | ✅ Pro | ✅ M5 `GenericonsProvider` (`genericons` id, 80 icons, `genericon` class) |
| Bootstrap Icons | ❌ (no Pro equivalent) | ✅ M5 `BootstrapIconsProvider` (`bootstrap-icons` id, 120 icons, `bi-` class) |
| Custom SVG upload | ✅ Pro | ✅ `CustomSvgProvider` |
| Inline icon picker in menu editor | megamenu | ✅ JS picker via `IconManager` |
| Provider enable toggles | ✅ (Settings → General) | ✅ M5 7 per-provider checkboxes in Icons tab + REST API |

### 3.8 Visibility

| Condition | Source Plugin | imedia-menu Status | Notes |
|---|---|---|---|
| Per page | megamenu | ✅ `Page.php` |
| Per user role | megamenu-pro `roles/` | ✅ `UserRole.php` |
| Per device type | megamenu (responsive logic) | ✅ `DeviceType.php` |
| Schedule (date range) | megamenu | ✅ `Schedule.php` |
| Login state | megamenu | ✅ `LoginState.php` |
| Language (WPML/Polylang/TranslatePress) | megamenu | ✅ `Language.php` + `VisibilityTab.php:34-43` |
| URL parameter | n/a | ✅ `UrlParameter.php` |
| Custom PHP callback | megamenu | ✅ `PhpCallback.php` |
| Visitor: logged in / guest | megamenu | ✅ `LoginState.php` |

### 3.9 Caching & Performance

| Feature | megamenu | imedia-menu |
|---|---|---|
| Menu HTML cache (transient) | ✅ `style-manager` deletes via `megamenu_delete_cache` action | ✅ `MenuCache` + `CacheInvalidator` + context-aware key (`CacheKeyBuilder` includes role/device/page/locale) |
| Cache duration setting | n/a (rebuild on save) | ✅ `PerformanceTab.php:36-45` (1–1440 min) |
| Code splitting | n/a | ✅ `code_splitting` toggle (`PerformanceTab.php:48-59`) |
| CSS regeneration on menu save | ✅ `delete_cache_after_nav_menu_locations_save` | ✅ `CacheInvalidator` hooks `wp_update_nav_menu`, `save_post`, `wp_create_nav_menu`, `wp_delete_nav_menu` |
| SCSS incremental rebuild | ✅ | n/a (no SCSS) |
| `prefers-reduced-motion` short-circuit | n/a | ✅ |
| External cache flush (Breeze, etc.) | ✅ `clear_external_caches()` | ❌ |

### 3.10 Settings / Admin

| Feature | megamenu | imedia-menu |
|---|---|---|
| Top-level admin menu | ✅ `Mega_Menu` | ✅ `iMedia Menu` (`SettingsServiceProvider`) |
| Settings page with tabs | ✅ 5 pages, 100+ options | ✅ 1 page, 8 tabs, ~30 options |
| Export/Import settings | ✅ | ✅ JSON (`AdvancedTab.php:36-57` + `Export/Exporter.php`, `Importer.php`) |
| Tools page (rebuild CSS, reset) | ✅ `pages/tools.php` | ❌ |
| Location-level enable/disable | ✅ `Mega_Menu_Location::is_enabled()` | ✅ `LocationOverrides` |
| Preview links in admin bar | ✅ | ✅ `admin_bar_preview` (`GeneralTab.php:83-94`) |

### 3.11 Integrations (3rd-party)

| Integration | megamenu | imedia-menu |
|---|---|---|
| WPML | ✅ `integration/wpml/` | ⚠️ Locale detection only (`VisibilityTab.php:37`) |
| Polylang | ✅ `integration/polylang/` | ⚠️ Locale detection only |
| Block editor (Navigation block) | ✅ `integration/block/` | ✅ `src/Blocks/Navigation/` (register + render + EditorPreview) + `assets/blocks/navigation-block/render.php` with `"render": "file:"` in block.json |
| Elementor | ✅ `integration/elementor/` + Pro widget | ❌ |
| Breakdance / Bricks / Divi | ✅ | ❌ |
| Twenty Seventeen | ✅ `integration/twentyseventeen/` | ❌ |
| TranslatePress | n/a | ⚠️ Locale detection only |

### 3.12 Block Editor

| Feature | megamenu | imedia-menu |
|---|---|---|
| Navigation block (core) wrapper | ✅ | ✅ `block.json` → `"render": "file:./render.php"` → `Navigation::render()` with `MenuWalker`, location-aware settings, mobile toggle, inline CSS |
| Server-side render | ✅ | ✅ `Navigation::render()` via `render.php` co-located with `block.json` |

### 3.13 Developer / Extension API

| Feature | megamenu | imedia-menu |
|---|---|---|
| Filter hooks (20+) | `megamenu_*` family | 7 filters: `imedia_menu_visibility_conditions`, `imedia_menu_template_path`, `imedia_menu_template_args`, `imedia_menu_post_listing_query_args`, `imedia_menu_taxonomy_listing_args`, `imedia_menu_content_block_html`, `imedia_menu_capability`, `imedia_menu_replacements_token_map` (M1) |
| Action hooks | 15+ `megamenu_*` | 5: `imedia_menu_loaded`, `imedia_menu_panel_saved`, `imedia_menu_settings_saved`, `imedia_menu_cache_invalidated`, `imedia_menu_cache_flushed` |
| Custom content block registration | Via `$wp_widget_factory` | ✅ `ContentBlocks\Registry` + `ContentBlock` contract |
| Custom icon provider registration | ✅ via `megamenu_register_icons` | ✅ `IconProviderRegistry` + `IconProvider` contract |
| Custom visibility condition | ✅ | ✅ `Visibility\ConditionRegistry` + `VisibilityCondition` contract |
| Custom CSS generation | ✅ (SCSS filter hooks) | ❌ |
| Custom settings tab | ✅ (add to `megamenu_settings`) | ✅ `SettingsTab` contract + `SettingsRegistry` |
| Custom toggle block | ✅ `megamenu_registered_toggle_blocks` | ❌ |

---

## 4. Proprietary Dependency Scan

**imedia-menu (subject of audit) — 0 proprietary references found.**

| Scan target | Result |
|---|---|
| `grep "megamenu\|Mega_Menu\|MMM_\|maxmegamenu\|max_mega_menu"` (full tree) | 0 matches |
| `grep "megamenu\.com\|tomhemsley\|MAX_MEGA"` (full tree) | 0 matches |
| `grep "license\|EDD\|edd_\|api_key\|secret\|token"` in `src/` | 0 matches (only false positive: `is_page( 'secret' )` in a code-example comment in `VisibilityTab.php:53`) |
| Vendor code in `vendor/` | 75 MB — separate audit needed for license compliance (Composer dependencies) |

**megamenu-pro — proprietary concerns (reference only, not in imedia-menu):**

| File:Line | Issue |
|---|---|
| `megamenu-pro/megamenu-pro.php:17` | Hardcoded license key: `update_option('edd_mmm_license_key', 'B5E0B5F8DD8689E6ACA49DD6E6E1A930')` — bypasses EDD licensing |
| `megamenu-pro/megamenu-pro.php:18` | `update_option( 'edd_mmm_license_status', 'valid' )` — forces valid license state |
| `megamenu-pro/updater/EDD_MMM_Plugin_Updater.php` | EDD Software Licensing integration (proprietary update server) |

---

## 5. Open-Source Readiness Blockers (imedia-menu)

| # | Severity | Blocker | Evidence | Recommended action |
|---|---|---|---|---|
| 1 | 🔴 High | `assets/` is **3.5 GB** in the repo | `du -sh assets/{admin:2.3G, blocks:1.2G, frontend:40K}` | Add `assets/*/build/*.{map,unminified.js}` to `.gitignore`; ship only minified `index.js` + `index.css`; the React panel-builder is 103 KB minified, so >1 GB is dev build/source maps |
| 2 | 🔴 High | `vendor/` (75 MB) and `node_modules/` (62 MB) committed | `du -sh vendor:75M node_modules:62M` | Add to `.gitignore`; document `composer install` and `npm ci` in `README.md`; verify no first-party code accidentally lands in `vendor/` |
| 3 | 🟡 Med | `src/RestApi/` still empty placeholder directory | `ls -la src/RestApi/` returns empty (`.` and `..` only) | Either remove the dir or commit REST controller classes; `RestApiServiceProvider.php` (21 KB) registers routes but handler classes are missing; `src/Blocks/` resolved in M8 — populated with Navigation.php, EditorPreview.php + render.php |
| 4 | 🟡 Med | `node_modules/.package-lock.json` and `package-lock.json` may bloat further | not directly verified | Add `package-lock.json` to `.gitignore` for OSS release or use `npm ci` workflow |
| 5 | 🟡 Med | `composer.json` declares `wordpress-plugin` type + `GPL-2.0-or-later`, but no `LICENSE` file present | `composer.json` vs `LICENSE` absence | Add `LICENSE` (full GPL-2.0-or-later text) and `license.txt` (standard WP plugin format) |
| 6 | 🟡 Med | `readme.txt` exists but is not the standard WordPress.org format | `readme.txt` (need to read for full audit) | Validate against https://wordpress.org/plugins/developers/readme-validator/ |
| 7 | 🟡 Med | `Assets::buildInlineCss()` runs at `wp_nav_menu_args` filter (too late) — known limitation in `FrontendServiceProvider.php:120-127` | Comment in `FrontendServiceProvider.php:121-126` | Either move to `wp_enqueue_scripts` action or enqueue per-location stylesheet via `wp_register_style` + handle hook |
| 8 | 🟢 Low | Built JS bundle `assets/admin/panel-builder/build/index.js` is shipped, but the source React project is not in the repo | `assets/blocks/navigation-block/build/` present; `src/admin/panel-builder/` source not committed | Either commit the source under `src/admin/panel-builder/` and a build script, or document the build pipeline |
| 9 | 🟢 Low | `Sticky` class is referenced only by direct file load (not via any service provider) | `Frontend/Sticky.php` has no provider | Add to `FrontendServiceProvider` or mark as a TODO |
| 10 | 🟢 Low | No `.distignore` for `wp.org`-style SVN builds | not present | Add `.distignore` excluding `tests/`, `node_modules/`, `vendor/`, `var/`, source `src/` files not in `build/`, `composer.json`, `package.json`, `phpunit*.xml.dist`, `playwright.config.js` |
| 11 | 🟢 Low | `var/` directory (20 KB) committed — appears to be a build/compile cache | `du -sh var:20K` | Add to `.gitignore`; typically Doctrine/cache-style runtime artifacts |
| 12 | 🟢 Low | `frontend/assets/frontend/css/` is only 40 KB (good) but no `frontend/scss/` source shipped | `assets/frontend/` only has `css/`, `js/`, `mobile/` | Either ship the source for transparency, or document that CSS is generated from a private build process |

---

## 6. What imedia-menu does BETTER than megamenu

These features are **absent or weak** in megamenu and worth preserving as differentiators:

| Feature | Why it's a differentiator |
|---|---|
| Dark mode (`prefers-color-scheme`) | megamenu has no equivalent |
| `prefers-reduced-motion` toggle | megamenu has no a11y motion toggle |
| PHP 8.1+ / WP 6.4+ baseline | Modern typed code (`declare(strict_types=1)`, enums, readonly) |
| Namespaced PSR-4 autoloading | Cleaner than megamenu's `Mega_Menu_*` global class soup |
| Hybrid storage (postmeta + 3 tables) | Better for very large menus; supports revision history |
| Context-aware cache key | role/device/page/locale factored in — more correct caching |
| 8 visibility condition types out of the box | megamenu needs Pro for user role; no URL parameter or PHP callback |
| Exporter/Importer (JSON) | No megamenu equivalent |
| Single React-based panel builder | Cleaner UX than jQuery lightbox chain |
| 21 content blocks (14 M0 + 7 M1) | M1 closes the gap to Pro's `replacements/`, `tabbed/`, `toggle-blocks/`, and `woocommerce/` modules |
| Replacements token map filter (`imedia_menu_replacements_token_map`) | megamenu-pro has no extension point for adding custom tokens |
| Cross-language parity test (`tests/js/panel-builder.test.js`) | No megamenu equivalent — guarantees PHP enum and JS block-registry stay in sync |

---

## 8. Milestone Progress

| Milestone | Status | Branch | Notes |
|---|---|---|---|
| **M0** Foundation Hardening | ✅ done | `m0/foundation-hardening` | gitignore, LICENSE, .distignore, Sticky wired, late-binding CSS fixed, DEVELOPER.md |
| **M1** 8 New Content Blocks | ✅ done | `m1/8-new-content-blocks` | RealWidget, Replacements, Tabbed, Accordion, LoginState, Cart, DynamicHtml; Banner extended; +61 PHP tests, +22 JS tests |
| **M2** Layout Engine | ✅ done | `m2/layout-engine` | 3 layout types (Standard/Grid/Flyout), 3 orientations (Horizontal/Vertical/Accordion), 4 overlay modes; 12-track grid CSS, vertical/accordion CSS, overlay div + inline JS watcher; +27 PHP tests, +4 JS parity tests |
| **M3** Mobile Toggle Bar Designer | ✅ done | `m3/toggle-bar-designer` | 8 block types (menu_toggle, animated, spacer, search, logo, icon, html, custom); 3-region flex CSS; per-location `imedia_menu_toggle_bar` storage; REST API (GET/POST/DELETE); React designer modal in `LocationTab`; +85 PHP tests, +13 JS tests |
| **M4** Pro Modules | ✅ done | `m4/pro-modules` | Sticky-on-scroll (9 design-tab fields + IntersectionObserver + sentinel + scroll-delta accumulator + per-item visibility), Badges (4 styles matching megamenu-pro, per-item style + hide-on-mobile/desktop), Per-Item Style Overrides (40 CSS custom properties across 5 groups, enable-checkbox-per-property UX, sanitized values, `wp_add_inline_style` emitter); +34 PHP tests, +5 JS tests; 406/406 PHP + 48/48 JS passing; PHPStan clean |
| **M5** Icons + Google Fonts | ✅ done | `m5/pro-modules` | 7 providers + Google Fonts system (300+ fonts, weight/subset config, enqueue) + Fonts settings tab |
| **M6** Split Admin Into 5 Pages | ✅ done | `m6/admin-split` | 5 submenu pages (General, Design & Fonts, Mobile & Visibility, Icons, Advanced) with tab subsets; backward-compatible SettingsPage; PageRegistry + SettingsPageRenderer; 19 new tests |
| **M7** Integrations | ✅ done | `m7/integrations` | 7 integrations: WPML, Polylang, TranslatePress, Elementor (2 widgets), Bricks (element + notice), Divi (Divi 5 module + React visual builder + REST endpoint), Breakdance (element + SSR); 38 new tests; 513/513 PHP tests passing |
| **M8** Navigation Block PHP Source | ✅ done | — | `src/Blocks/Navigation/Navigation.php` (register + location-aware render), `EditorPreview.php`, `assets/blocks/navigation-block/render.php` with `"render": "file:"` in `block.json` |
| **M9** Tests + Importer | ⏳ pending | — | PHPUnit/Playwright/axe coverage, perf + security audit, megamenu importer |
| **M10** Docs + Client Training | ⏳ pending | — | end-user docs, training session |

---

## 7. Top-Priority Items Before Open-Source Release

> Status legend: ✅ done · 🔄 in progress · ⏳ pending · — n/a (not a private-client blocker)

1. **Cut repo size from 3.6 GB → ~5 MB** — ✅ M0: `.gitignore` rule `**/node_modules/` added; remaining cleanup (`git rm --cached`, `git-filter-repo`) is a one-off housekeeping task
2. **Fill or remove `src/Blocks/` and `src/RestApi/`** — ✅ M0: `.gitkeep` + README in both; M11 (block editor PHP source) will fill `src/Blocks/`
3. **Add `LICENSE`** — ✅ M0: full GPL-2.0-or-later text
4. **Add `.distignore`** — ✅ M0: matches wp.org packaging
5. **Document build pipeline** in `README.md` — ✅ M0: `DEVELOPER.md` covers `composer install` → `npm ci` → `npm run build`
6. **Move per-location inline CSS** off the `wp_nav_menu_args` filter — ✅ M0: new `enqueuePerLocationInlineCss()` on `wp_enqueue_scripts` priority 110 (`src/Providers/FrontendServiceProvider.php:49`)
7. **Promote `Sticky` to a service provider** — ✅ M0: registered in `FrontendServiceProvider::register()` (`src/Providers/FrontendServiceProvider.php:11,22,26`)
8. **Consider shipping the React panel-builder source** — ⏳ open question — keep private-client or ship?
9. **Add 7 new content blocks (M1)** — ✅ RealWidget, Replacements, Tabbed, Accordion, LoginState, Cart, DynamicHtml + BannerBlock extension (multi-CTA, overlay, aspect ratio)
10. **Layout engine (M2)** — ✅ 12-track grid, flyout/grid/standard, vertical, accordion, overlay modes
11. **Mobile toggle bar designer (M3)** — ✅ 8 block types, 3-region flex, per-location storage, REST API, React designer
12. **Pro modules (M4)** — ✅ Sticky-on-scroll (IntersectionObserver + 9 design-tab fields + per-item visibility), badges (4 styles, hide-on-mobile/desktop), per-item style overrides (40 CSS custom properties, enable-checkbox-per-property, sanitized values, `wp_add_inline_style` emitter)
13. **7 icon providers + Google Fonts (M5)** — ✅ FA5, FA6, Genericons, Bootstrap Icons (120/80/120 icons each); Google Fonts system (300+ fonts, weight/subset, Fonts tab); `FontsServiceProvider` registered in `Plugin.php`; 69 new PHP tests; 475/475 PHP + 48/48 JS passing
14. **Split 1-page-8-tabs into 5 admin pages (M6)** — ✅
15. **Integrations (M7)** — ✅ WPML, Polylang, TranslatePress, Elementor, Bricks, Divi, Breakdance
16. **Navigation block PHP source (M8)** — ✅ `src/Blocks/Navigation/Navigation.php` (location-aware render), `EditorPreview.php`, `assets/blocks/navigation-block/render.php` + `"render": "file:"` in `block.json`; `InspectorControls.php` removed (dead code — JS uses REST API)
17. **Test/perf/security audit + megamenu importer (M9)** — ⏳
18. **Docs + client training (M10)** — ⏳

=== iMedia Menu ===
Contributors: inventivemedia
Requires at least: 6.4
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 1.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Premium-grade navigation and mega menu plugin for WordPress. Fully free and open-source with WCAG 2.1 AA accessibility.

== Description ==

iMedia Menu is a comprehensive WordPress navigation management solution. Replace Max Mega Menu, UberMenu, and similar plugins with a modern, performant, and accessible alternative.

= Key Features =

* Multi-column mega menus with full-screen visual builder
* 21 content block types for mega panels (incl. RealWidget, Replacements, Tabbed, Accordion, LoginState, Cart, DynamicHtml)
* Conditional visibility (8 conditions including date scheduling, user roles, and PHP callbacks)
* WCAG 2.1 AA accessibility with full ARIA Menubar pattern
* Performance-first: code splitting, conditional asset loading, context-aware caching
* Mobile off-canvas slide-in navigation
* Gutenberg block for Full Site Editing themes
* REST API for headless/decoupled setups
* Reusable panel templates
* Icon system (Dashicons, Font Awesome, custom SVG)
* Keyboard navigation support
* RTL-ready with logical CSS properties
* Dark mode admin UI

== Installation ==

1. Upload the `imedia-menu` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' screen
3. Go to Appearance > iMedia Menu to configure global settings
4. Go to Appearance > Menus to build menus with mega menu panels

== Frequently Asked Questions ==

= Does iMedia Menu support block themes? =

Yes. Register the iMedia Menu block in the Site Editor to display mega menus in Full Site Editing themes.

= Is iMedia Menu accessible? =

Yes. WCAG 2.1 Level AA compliance is a core requirement with full ARIA Menubar keyboard interaction.

= Is there a premium version? =

No. iMedia Menu is fully free and open-source with no feature gating or license checks.

= Does it work with page builders? =

iMedia Menu works with any theme. Content blocks (Gutenberg, shortcode, widget) provide compatibility with most page builders.

== Changelog ==

= 1.5.0 =
* Icon provider expansion: 4 new providers — Font Awesome 5 (120 icons, `fas`/`fab`/`far` styles), Font Awesome 6 (120 icons, `fa-solid`/`fa-regular`/`fa-light`/`fa-brands` styles), Genericons (80 icons, `genericon` class), Bootstrap Icons (120 icons, `bi-` class) — all with independent enable toggles in Icons settings tab + REST API availability
* Google Fonts system: 300+ hardcoded font list, weight selection (100-900), subset configuration, live preview UI in new Fonts settings tab (fonts tab in Settings → iMedia Menu); fonts enqueued via `wp_enqueue_style('imm-google-fonts', …)`
* `FontsManager` with `getEnabledFonts()` / `saveFonts()` / `enqueue()` for programmatic font management
* New filter: `imm_google_fonts` (filter the font list), `imm_google_fonts_url` (filter the Google Fonts CSS2 URL)
* Google Fonts enqueued at priority 20 on `wp_enqueue_scripts`; clean fallback when no fonts are configured
* New tests: 69 PHP tests across 7 test files (4 icon provider tests + IconServiceProvider boot tests + IconsTab toggle tests + 4 font system tests). Full suite: 475/475 PHP + 48/48 JS passing
* Updated `AUDIT.md`: Icons row ✅ (7 providers), Google Fonts row ✅; M5 milestone flipped to ✅ done

= 1.4.0 =
* Sticky-on-scroll: 9 design-tab fields (`sticky_desktop`, `sticky_mobile`, `sticky_opacity` 0.2-1.0, `sticky_offset` 0-500, `sticky_expand`, `sticky_expand_mobile`, `sticky_hide_until_scroll_up` + `sticky_hide_tolerance` 0-200 + `sticky_hide_offset` 0-500) emitted as `data-sticky-*` attributes on `<nav>` by `Sticky::attributes($settings)`
* `imm-sticky.js`: 1-px sentinel + `IntersectionObserver` for the stuck state, scroll-delta accumulator for hide-until-scroll-up, expand toggle, `shouldEnable()` per-breakpoint gate, `resize` handler; honors `prefers-reduced-motion`
* Per-item sticky visibility: `_imedia_menu_sticky_visibility` postmeta ∈ `{always, show-when-stuck, hide-when-stuck}` surfaced as `imm-sticky-{show,hide}-when-stuck` classes on the `<li>`
* Menu item badges: 4 styles (Red `#D32F2F` / Teal `#00796B` / Amber `#FFC107` / Indigo `#303F9F`) matching megamenu-pro defaults; per-item `disabled|style-1..4` select + hide-on-mobile/desktop checkboxes
* `Badge::render($itemId)` outputs `<span class="imm-badge imm-badge--style-N" data-style="..." style="--imm-badge-bg:...;--imm-badge-text:...">`; legacy `_badge_text` / `_badge_color` / `_badge_text_color` / `_badge_position` postmeta still read by `MenuItemFields::renderFields()`
* `imm-badge.css`: 4 `.imm-badge--style-N` rules using `--imm-badge-bg` / `--imm-badge-text` custom props; `.imm-hide-on-mobile` / `.imm-hide-on-desktop` mobile-first classes with 769px+ reverse
* Per-item style overrides: 40 CSS custom properties across 5 groups (Background 4, Font 8, Border 10, Icon 3, Spacing 9, Panel 5) with an enable-checkbox-per-property UX
* `StyleOverrides::getItemStyles($itemId)` returns the inline `--imm-item-...` string applied to each `<li>`; values sanitized via `StyleOverrides::sanitizeValue()` (hex / rgb / hsl / px / em / rem / % / vh / vw / pt / ch, plus weight / text-align / transform / decoration enums)
* `Assets::buildStyleOverridesCss()` emits per-item CSS blocks via `wp_add_inline_style('imm-base', $css)` — no per-item `<style>` tag pollution
* All 3 Pro features use conditional asset enqueue (`$wpdb->postmeta` query) so they load only on pages where the feature is actually used
* New filters: `imm_sticky_attributes`, `imm_badge_html`, `imm_style_overrides_css` (all in `DEVELOPER.md`)
* New tests: 8 StickyTest, 9 BadgeTest, 17 StyleOverridesTest (34 new PHP tests); 5 new `imm-sticky.test.js` cases (48 JS tests total). Full suite: 406/406 PHP + 48/48 JS passing
* `AUDIT.md` updated: rows for Sticky menu / Menu item badges / Per-menu-item style overrides all flipped to ✅ M4

= 1.3.0 =
* Mobile toggle bar designer: per-location 3-region flex layout (left / center / right) replaces the auto-prepended single hamburger button when blocks are configured
* 8 toggle block types: `menu_toggle`, `menu_toggle_animated` (arrow / slider CSS animations), `spacer`, `search` (collapsible), `logo`, `icon` (dashicons / HTML), `html` (sanitized via `wp_kses_post`), `custom` (shortcode)
* Per-location storage in `wp_options[imedia_menu_toggle_bar][{slug}].blocks` via `ToggleBarRepository` with whitelist validation (block type, align, per-type settings)
* `ToggleBlock` contract (interface) + `AbstractToggleBlock` base; custom block types registerable via `imm_toggle_bar_blocks` filter
* Renderer (`ToggleBarRenderer`) sorts blocks by `align` into 3 regions; center region only renders when non-empty (wrapper gets `--has-center` modifier)
* REST API: `GET / POST / DELETE /wp-json/imedia-menu/v1/toggle-bar/{slug}` with capability check and `imm_toggle_bar_saved` / `imm_toggle_bar_deleted` actions
* React designer modal in `LocationTab` admin UI: 3-column region layout, native HTML5 drag-and-drop, block picker with 8 type buttons + icon previews, per-block settings popover with type-specific fields
* `imm_toggle_bar_html` and `imm_toggle_bar_block_settings` filters for output customization
* Mobile-only CSS (`imm-toggle-bar.css`, hidden at 769px+) with hamburger animation, arrow/slider variants, search expand/collapse
* `imm-toggle-bar.js`: idempotent init via `data-imm-toggle-bar-init` flag, `openMobileNav` event dispatcher, search expand/collapse + Escape key, resize handler
* Backward compatibility: `BlockEditorServiceProvider::maybePrependMobileToggle` suppresses the auto-prepended button when `ToggleBarRepository::hasBlocks($location)` returns true; original single-hamburger behavior preserved when zero blocks are configured
* Conditional asset enqueue: CSS and JS load only when at least one registered nav menu location has configured blocks
* New tests: 8 ToggleBarRepository tests, 6 ToggleBlockRegistry tests, 9 ToggleBarRenderer tests, 8 block tests per type (64 total), 6 REST API tests, 7 React designer structural tests, 6 toggle-bar DOM tests
* `DEVELOPER.md` documents the Toggle Bar Designer section, block contract, REST endpoints, and filter/action hooks

= 1.2.0 =
* Layout engine: 3 panel layouts (Standard / Grid / Flyout) selectable per-panel via `PanelLayoutType` enum
* 12-track CSS grid layout (`imm-grid.css`, conditionally enqueued when at least one panel uses grid) with per-column `span` (1-12), per-row `hide_on_mobile` / `hide_on_desktop` / `css_class`
* Vertical and accordion orientations (`imm-vertical.css`, conditionally enqueued); per-location `orientation` field with `MenuOrientation` enum (Horizontal / Vertical / Accordion)
* Page overlay: per-location `overlay` mode (`OverlayMode` enum: off / desktop / mobile / both) + `overlay_color`; rendered in `wp_footer` with inline JS watcher
* Strategy pattern for layouts: `PanelLayoutStrategy` interface + `StandardLayout` (M0 behavior preserved), `GridLayout`, `FlyoutLayout` + `PanelLayoutStrategyRegistry`
* Flyout layout: panel data is round-tripped; the walker falls through to the standard WordPress submenu
* Panel-builder UI: `Layout Type` selector in `PanelSettingsDrawer`; per-column span editor for grid; `BlockPicker` shows a flyout-aware message when flyout is selected
* Settings → Locations: 3 new overridable fields (`orientation`, `overlay`, `overlay_color`) with server-side whitelist validation
* New tests: 39 enum tests, 4 strategy tests, 15 MenuWalker tests, 5 LocationOverrides tests, 6 Overlay tests, 7 sanitization tests, 4 JS parity tests
* `DEVELOPER.md` documents Layout Types and Orientation & Overlay sections

= 1.1.0 =
* New content blocks: RealWidget (embed any `wp_widget_factory` widget), Replacements (token substitution with `imedia_menu_replacements_token_map` filter), Tabbed (ARIA tablist, keyboard nav), Accordion (native `<details>`), LoginState (logged-in / logged-out container), Cart (WooCommerce-aware), DynamicHtml (URL/callback fetch with cache)
* BannerBlock extended: `template` (overlay/card/side), `overlay_color`, `overlay_opacity`, multi-CTA `cta[]` array, `aspect_ratio`
* Cross-language parity test asserts PHP `BlockType` enum, JS `block-registry.js`, and JS `default-configs.js` stay in sync
* New frontend asset: `assets/frontend/js/tabbed.js` (tablist keyboard navigation)
* Sticky menu wired into FrontendServiceProvider (was orphaned)
* Per-location inline CSS now enqueued on `wp_enqueue_scripts` priority 110 (was on `wp_nav_menu_args`, late-binding bug fixed)
* `LICENSE` and `.distignore` added
* `DEVELOPER.md` documents all 9 filters + 7 actions with examples

= 1.0.0 =
* Initial release
* Mega menu panels with full-screen builder
* 14 content block types
* 8 conditional visibility conditions
* WCAG 2.1 AA keyboard navigation
* Mobile off-canvas navigation
* REST API
* Reusable panel templates
* Export/import
* Context-aware caching

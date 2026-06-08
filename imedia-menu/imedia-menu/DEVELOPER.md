# Developer Guide

> Extension points, architecture overview, and local development setup for
> iMedia Menu. End-user documentation lives in `readme.txt`; this file is for
> engineers integrating with or contributing to the plugin.

---

## Requirements

| Tool    | Version  | Notes                                                       |
| ------- | -------- | ----------------------------------------------------------- |
| PHP     | 8.1+     | Strict types declared throughout. PHP 8.0 will not boot.    |
| WordPress | 6.4+   | `get_registered_nav_menus()`, FSE block scaffolding.        |
| Composer | 2.x    | Dev dependencies (PHPUnit, PHPStan, PHPCS).                 |
| Node.js | 18+      | Build pipeline (webpack, babel, sass) per sub-bundle.       |
| npm     | 9+       | Pin via `npm ci` to keep lockfile reproducible.             |

---

## Local Development Setup

```bash
# 1. Install PHP dev dependencies (PHPUnit, PHPStan, PHPCS, WPCS, etc.)
composer install

# 2. Install JS dev dependencies for all three sub-bundles
#    (settings-page, panel-builder, navigation-block).
#    The lockfile pins exact versions — use `ci`, not `install`.
npm ci

# 3. Build frontend assets
npm run build
#    This runs build:settings, build:panel, and build:block in sequence.
#    Outputs are committed to assets/{admin,blocks}/*/build/ so end-users
#    do not need a build step.

# 4. Symlink or drop the plugin into wp-content/plugins/imedia-menu
ln -s "$PWD" /path/to/wordpress/wp-content/plugins/imedia-menu
```

### Running Tests

```bash
# Unit tests (112 tests, ~30ms, no WP test framework required)
composer phpunit

# PHP static analysis
composer phpstan

# WordPress coding standards check
composer phpcs

# Auto-fix coding standards violations
composer phpcbf

# JS unit tests (Jest)
npm run test:js

# E2E tests (Playwright — requires running WordPress instance)
npm run test:e2e
```

PHPUnit 10 is configured via `phpunit-unit.xml.dist` (unit suite, no
`wp-phpunit` bootstrap) and `phpunit.xml.dist` (integration suite, requires
a configured WP test environment). The unit suite runs in 30ms with 220
assertions and is the primary fast-feedback loop.

---

## Architecture

```
imedia-menu/
├── imedia-menu.php            # Plugin entry point (defines constants, boots Plugin)
├── src/
│   ├── Plugin.php             # Service-provider orchestrator (admin vs frontend branch)
│   ├── Activator.php          # install/upgrade routines, schema migration, defaults
│   ├── Deactivator.php        # clears transients, leaves data intact
│   ├── Admin/                 # Settings pages, menu editor, mega panel UI
│   ├── Cache/                 # MenuCache, CacheKeyBuilder, CacheInvalidator
│   ├── ContentBlocks/         # 14 block types + Registry
│   ├── Contracts/             # Interfaces: ServiceProvider, etc.
│   ├── Database/              # MigrationRunner, Schema, repositories
│   ├── Export/                # Importer/Exporter (JSON)
│   ├── Frontend/              # Assets, MenuWalker, Sticky, MobileNav
│   ├── Providers/             # One ServiceProvider per subsystem
│   ├── RestApi/               # (placeholder — filled in M8)
│   ├── Blocks/                # (placeholder — navigation block PHP source in M8)
│   ├── Visibility/            # ConditionEvaluator, ConditionRegistry
│   └── Icons/                 # Icon providers (Dashicons, FA, custom SVG)
├── assets/
│   ├── frontend/              # Production CSS+JS shipped to end users
│   ├── admin/                 # settings-page + panel-builder React apps
│   └── blocks/                # navigation-block
└── tests/
    ├── php/Unit/              # Fast isolated tests
    ├── php/Integration/       # WP test framework tests
    ├── js/                    # Jest tests
    └── e2e/                   # Playwright tests
```

### Service Providers

`Plugin::boot()` (`src/Plugin.php:30`) registers a different provider set
based on the WP context:

- **Always loaded**: Cache, Visibility, RestApi, Icon, Template, Migration, BlockEditor
- **Admin only**: Settings, Admin, MenuEditor, MegaPanel, Revision
- **Frontend only**: Frontend, Mobile

Each provider implements `IMedia\Menu\Contracts\ServiceProvider` with two
methods: `register()` (instantiate dependencies) and `boot()` (wire hooks).
A new subsystem should be added as a provider — do not call
`add_action`/`add_filter` from the main plugin file.

### Storage Layout

- **Global settings**: `wp_options.imedia_menu_settings` (single row)
- **Per-location overrides**: `wp_options.imedia_menu_location_overrides`
  (array of `{slug: settings}`)
- **Mega panel configs**: `wp_imedia_menu_panels` (JSON column for layout)
- **Reusable templates**: `wp_imedia_menu_templates`
- **Revision history**: `wp_imedia_menu_revisions`
- **Per-item meta** (icons, badges, descriptions, visibility): `wp_postmeta`
  with keys prefixed `_imedia_menu_`

The hybrid model is intentional: per-item settings are small and read with
the menu walker, so postmeta is fine. Panel layouts are large JSON blobs
that would bloat the meta table and slow down menu queries, so they live in
a dedicated table.

### Caching

`MenuCache` is a thin wrapper around WordPress transients with a
deterministic key generated by `CacheKeyBuilder`. Keys include the user
role hash, device class, current post ID, and locale — so menu HTML is
shared across anonymous users in the same view but never served to
authenticated users or the admin.

`CacheInvalidator` listens to `wp_update_nav_menu`, `imedia_menu_*_saved`,
`switch_theme`, and `save_post` to clear the relevant cache keys. The
entire menu cache is flushed on theme switch; per-menu keys are dropped
when the corresponding menu is updated.

---

## Extension API

iMedia Menu exposes 9 filters and 7 actions for third-party code. Hook
into these from a regular WordPress plugin or `mu-plugin` — do not edit
plugin source.

### Filters

#### `imedia_menu_capability`

Default: `'edit_theme_options'`. The capability required to access the
mega panel builder, settings, and REST API mutations. Used by
`SettingsServiceProvider::register()` (admin menu page) and
`RestApiServiceProvider::permissionCallback()` (every route).

```php
// Restrict to super-admins only.
add_filter( 'imedia_menu_capability', fn() => 'manage_options' );
```

#### `imedia_menu_template_path`

```php
apply_filters( 'imedia_menu_template_path', string $path, string $templateName )
```

Override the resolved path for a template before it is required. Useful
for theme authors who want to ship custom templates in their theme.

```php
add_filter( 'imedia_menu_template_path', function ( $path, $name ) {
    $theme_override = get_stylesheet_directory() . '/imedia-menu/' . $name;
    return file_exists( $theme_override ) ? $theme_override : $path;
}, 10, 2 );
```

#### `imedia_menu_template_args`

```php
apply_filters( 'imedia_menu_template_args', array $args, string $templateName )
```

Modify the variables extracted into a template's scope. Add custom data
your theme templates depend on.

```php
add_filter( 'imedia_menu_template_args', function ( $args, $name ) {
    $args['site_name'] = get_bloginfo( 'name' );
    return $args;
}, 10, 2 );
```

#### `imedia_menu_visibility_conditions`

```php
apply_filters( 'imedia_menu_visibility_conditions', array $conditions )
```

Register additional visibility conditions. The argument is an associative
array of `id => IMedia\Menu\Visibility\Condition` (or any object exposing
`matches(): bool`). Conditions are evaluated in order against the current
request.

```php
add_filter( 'imedia_menu_visibility_conditions', function ( $conditions ) {
    $conditions['user_has_subscription'] = new My_Subscription_Condition();
    return $conditions;
} );
```

#### `imedia_menu_search_form_html`

```php
apply_filters( 'imedia_menu_search_form_html', string $html, array $config )
```

Replace the rendered search form HTML inside the Search content block.
`$config` contains the block's stored config (placeholder, style, icon_only).

```php
add_filter( 'imedia_menu_search_form_html', function ( $html, $config ) {
    return str_replace( 'class="imm-search"', 'class="imm-search my-search"', $html );
}, 10, 2 );
```

#### `imedia_menu_post_listing_query_args`

```php
apply_filters( 'imedia_menu_post_listing_query_args', array $args, array $config )
```

Modify the `WP_Query` arguments used by the Post Listing content block.
Receives the default args and the block config.

```php
// Exclude a category from all post listings.
add_filter( 'imedia_menu_post_listing_query_args', function ( $args, $config ) {
    $args['category__not_in'] = array( 42 );
    return $args;
}, 10, 2 );
```

#### `imedia_menu_taxonomy_listing_args`

```php
apply_filters( 'imedia_menu_taxonomy_listing_args', array $args, array $config )
```

Modify the `get_terms()` arguments used by the Taxonomy Listing block.

```php
add_filter( 'imedia_menu_taxonomy_listing_args', function ( $args, $config ) {
    $args['exclude'] = array( 1, 2, 3 );
    return $args;
}, 10, 2 );
```

#### `imedia_menu_content_block_html`

```php
apply_filters( 'imedia_menu_content_block_html', string $html, array $block, mixed $menuItemId )
```

Modify the final HTML for any content block before it is returned to the
walker. Runs once per block, after the block's own `render()` has produced
its output. `$block` is the block config array; `$menuItemId` is the parent
menu item ID (or `null` for top-level blocks).

```php
add_filter( 'imedia_menu_content_block_html', function ( $html, $block ) {
    if ( 'image' === ( $block['type'] ?? '' ) ) {
        $html = str_replace( '<img', '<img loading="lazy"', $html );
    }
    return $html;
}, 10, 2 );
```

#### `imedia_menu_replacements_token_map`

```php
apply_filters( 'imedia_menu_replacements_token_map', array $tokens, array $config )
```

Add or override tokens in the Replacements content block (`ContentBlocks/ReplacementsBlock`).
Receives the default token map (keys are token names with braces, values are
their resolved strings) and the block config. Return a new map. Built-in
tokens are `{{user_name}}`, `{{user_email}}`, `{{user_id}}`, `{{user_role}}`,
`{{date}}`, `{{year}}`, `{{month}}`, `{{day}}`, `{{site_title}}`, `{{site_url}}`,
`{{ip}}`. Token sources silently fall back to empty string if unavailable
(WC inactive, no logged-in user, etc.) — wrap a token in `[if:token]…[/if]`
to suppress its container when empty.

```php
// Add a {{current_page_title}} token.
add_filter( 'imedia_menu_replacements_token_map', function ( $tokens, $config ) {
    $tokens['{{current_page_title}}'] = get_the_title();
    return $tokens;
}, 10, 2 );
```

### Actions

#### `imedia_menu_loaded`

```php
do_action( 'imedia_menu_loaded' )
```

Fires once, after `Plugin::boot()` finishes wiring all providers. Use this
as the bootstrap point for code that depends on iMedia Menu being fully
initialised.

```php
add_action( 'imedia_menu_loaded', function () {
    // Safe to call iMedia\Menu\Plugin::instance() here.
    $plugin = \IMedia\Menu\Plugin::instance();
} );
```

#### `imedia_menu_settings_saved`

```php
do_action( 'imedia_menu_settings_saved', array $settings )
```

Fires after the global settings option is updated via the REST API.

```php
add_action( 'imedia_menu_settings_saved', function ( $settings ) {
    if ( ! empty( $settings['dark_mode_enabled'] ) ) {
        // Warm cache, ping CDN purge endpoint, etc.
    }
} );
```

#### `imedia_menu_location_overrides_saved`

```php
do_action( 'imedia_menu_location_overrides_saved', string $slug, array $overrides )
```

Fires after a per-location override is saved.

```php
add_action( 'imedia_menu_location_overrides_saved', function ( $slug, $overrides ) {
    error_log( "Overrides updated for location: {$slug}" );
}, 10, 2 );
```

#### `imedia_menu_panel_saved`

```php
do_action( 'imedia_menu_panel_saved', int $menuItemId )
```

Fires after a mega panel is saved. Triggers `CacheInvalidator` to clear
the panel's cache and the parent menu's cache.

```php
add_action( 'imedia_menu_panel_saved', function ( $menuItemId ) {
    // Notify an external search index, e.g. Algolia or Elasticsearch.
} );
```

#### `imedia_menu_template_saved`

```php
do_action( 'imedia_menu_template_saved', int $id, array $body )
```

Fires after a reusable template is saved.

```php
add_action( 'imedia_menu_template_saved', function ( $id, $body ) {
    // Sync template to a remote CMS, or snapshot to S3.
}, 10, 2 );
```

#### `imedia_menu_cache_invalidated`

```php
do_action( 'imedia_menu_cache_invalidated', int $menuId )
```

Fires when the cache for a specific menu is cleared. Distinct from
`imedia_menu_cache_flushed` (which clears everything).

```php
add_action( 'imedia_menu_cache_invalidated', function ( $menuId ) {
    // Pre-warm the cache, or notify Varnish.
} );
```

#### `imedia_menu_cache_flushed`

```php
do_action( 'imedia_menu_cache_flushed' )
```

Fires when the entire iMedia Menu cache is cleared. Currently triggered
by `switch_theme` and global settings updates.

```php
add_action( 'imedia_menu_cache_flushed', function () {
    // Purge page cache, CDN, etc.
} );
```

---

## Adding a New Content Block

1. Create `src/ContentBlocks/MyBlock.php` implementing
   `IMedia\Menu\Contracts\ContentBlock` (the interface; no base class).
2. Implement `type(): string`, `defaultConfig(): array`, and
   `render( array $config, array $styles ): string`.
3. Register it in `ContentBlocks\Registry::registerDefaults()`
   (`src/ContentBlocks/Registry.php:53`).
4. **If the block is a container** (has child blocks, e.g. Tabbed, Accordion,
   LoginState), add a `setRegistry( Registry $registry ): void` method. The
   registry injects itself into every container block automatically — see
   `Registry::render()` which calls `method_exists($handler, 'setRegistry')`
   before rendering. Call `$this->registry->render($childConfig, $styles,
   $menuItemId)` to recurse.
5. **If the block is a singleton widget host** (e.g. RealWidget), instantiate
   the inner widget lazily inside `render()` via `the_widget()` — do not
   store it as a property, to avoid re-running widget constructors across
   multiple render calls.
6. Add PHPUnit coverage in `tests/php/Unit/ContentBlocks/MyBlockTest.php`.
7. **Cross-language parity**: when you add the block, the PHP `BlockType`
   enum case, the JS `block-registry.js` entry, and the JS `default-configs.js`
   key must all be added in the same commit. The
   `tests/js/panel-builder.test.js` suite asserts this automatically — if
   the test fails, one of the three files is out of sync.
8. Add a Jest test if the block emits DOM the frontend scripts interact
   with (e.g. tabbed.js for Tabbed, accordion-toggle.js for Accordion).

### Built-in blocks (M0)

MenuLinks, Heading, Text, Icon, Image, Banner, Gutenberg, Widget, Html,
Shortcode, PostListing, TaxonomyListing, Search, Divider.

### Built-in blocks (M1)

| Slug | Class | Notes |
|---|---|---|
| `real_widget` | `RealWidgetBlock` | Embeds any registered `wp_widget_factory` widget by id. |
| `replacements` | `ReplacementsBlock` | Token substitution; `apply_filters('do_shortcode')`; `wp_kses` sanitized. Exposes `imedia_menu_replacements_token_map`. |
| `tabbed` | `TabbedBlock` | ARIA tablist, vertical/horizontal, keyboard nav (tabbed.js). Container. |
| `accordion` | `AccordionBlock` | Native `<details>/<summary>`. Container. |
| `login_state` | `LoginStateBlock` | Container with `logged_in` + `logged_out` child blocks. `fallback: hide\|empty`. |
| `cart` | `CartBlock` | WooCommerce cart link + item count. No-op if WC inactive. |
| `dynamic_html` | `DynamicHtmlBlock` | Fetches HTML from URL or PHP callback; cached in `wp_cache_*`. |

`BannerBlock` is M0 but extended in M1 with `template` (overlay/card/side),
`overlay_color`, `overlay_opacity`, multi-CTA `cta[]` array, and `aspect_ratio`.
The multi-CTA array suppresses the link wrap (only the primary CTA stays
inside the link).

## Layout Types

Panels can render in three layouts. The active layout is stored on the panel
itself (`panel.config.layout_type`) and decoded by the `PanelLayoutType`
enum (`src/Enums/PanelLayoutType.php`).

| Layout | Enum value | Class | What it does |
|---|---|---|---|
| Standard (legacy) | `columns` | `Frontend/PanelLayout/StandardLayout` | The M0/M1 behavior — arbitrary row/column count, width set per column. |
| Grid | `grid` | `Frontend/PanelLayout/GridLayout` | 12-track CSS grid; per-column `span` 1-12; per-row `hide_on_mobile` / `hide_on_desktop` / `css_class`. |
| Flyout | `flyout` | `Frontend/PanelLayout/FlyoutLayout` | Skips panel rendering entirely; the menu item falls through to the standard WordPress submenu. |

The renderer dispatches to the right strategy via
`Frontend/PanelLayout/PanelLayoutStrategyRegistry`. Adding a new layout
type:

1. Add a case to `PanelLayoutType`.
2. Create `src/Frontend/PanelLayout/MyLayout.php` implementing
   `PanelLayoutStrategy` (`render($panel, $menuItemId): string` and
   `requiredStylesheet(): ?string`).
3. Register it in `PanelLayoutStrategyRegistry`.

The frontend enqueues `assets/frontend/css/imm-grid.css` only when at
least one enabled panel uses `grid`, and
`assets/frontend/css/imm-vertical.css` only when at least one location
uses a non-horizontal orientation (see `Assets::enqueueLayoutAssets()`).

## Orientation & Overlay

Per-location, configured in **Settings → Locations**:

| Setting | Enum | Values |
|---|---|---|
| `orientation` | `MenuOrientation` | `horizontal` (default), `vertical`, `accordion` |
| `overlay` | `OverlayMode` | `off` (default), `desktop`, `mobile`, `both` |
| `overlay_color` | string | CSS color/rgba value, default `rgba(0,0,0,0.3)` |

`MenuOrientation::requiredTriggerType()` returns `'click'` for Accordion
(forcing click trigger) and `null` for Horizontal/Vertical (preserving
the user's `trigger_type` setting). The accordion behavior is
implemented in `assets/frontend/css/imm-vertical.css` (submenus always
visible).

`Overlay` (`src/Frontend/Overlay.php`) prints a
`<div class="imm-page-overlay" hidden>` in `wp_footer` for every
location whose `overlay` mode isn't `off`, plus a 30-line inline JS
watcher that toggles the overlay on `aria-expanded="true"` on any
`.imm-nav .imm-link`. Clicking the overlay closes any open submenu.
Set `--imm-overlay-color` on the div (already done by Overlay) to
change the dim color.

## Toggle Bar Designer (M3)

The mobile toggle bar replaces the default auto-prepended hamburger with a
flex-based 3-region layout (left/center/right) that can hold up to 8 block
types. It is configured **per location** through the `LocationTab` admin UI.

### Storage

```
wp_options[imedia_menu_toggle_bar][primary] = {
  blocks: [
    { id: "b1", type: "logo", align: "left",  settings: { logo_id: 42, url: "/", target: "_self" } },
    { id: "b2", type: "menu_toggle_animated", align: "right", settings: { animation: "arrow" } },
  ]
}
```

Use `IMedia\Menu\Frontend\ToggleBar\ToggleBarRepository` to CRUD this data
programmatically.

### Built-in block types

| Type | Label | Notes |
|---|---|---|
| `menu_toggle` | Menu Toggle | Classic 3-line hamburger; opens the off-canvas |
| `menu_toggle_animated` | Animated Menu Toggle | `animation`: `arrow` (default) or `slider` |
| `spacer` | Spacer | `width` (CSS length) |
| `search` | Search | Collapsible search input; `placeholder`, `action` |
| `logo` | Logo | `logo_id` (attachment), `url`, `target`, `alt` |
| `icon` | Icon | `icon` (dashicon name or HTML), `url`, `aria_label` |
| `html` | Custom HTML | `content` (sanitized via `wp_kses_post`) |
| `custom` | Shortcode | `shortcode` (passed through `do_shortcode`) |

### Block contract

Every block implements `IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock`:

```php
interface ToggleBlock {
    public function type(): string;
    public function label(): string;
    public function defaultSettings(): array;
    public function validate( array $settings ): array;
    public function render( array $settings, array $args ): string;
    public function requiredStylesheet(): ?string;
    public function requiredScript(): ?string;
}
```

### Registering a custom block

```php
add_action( 'imm_toggle_bar_blocks', function ( $registry ) {
    $registry->register( new My\Custom\PhoneBlock() );
} );
```

`PhoneBlock` implements `ToggleBlock` and returns a `<a href="tel:...">` element
from `render()`. Blocks are auto-discovered by the renderer on every request.

### Filter hooks

- `imm_toggle_bar_blocks` (filter) — register additional block types
- `imm_toggle_bar_html` (filter) — modify the final toggle bar HTML
- `imm_toggle_bar_block_settings` (filter) — override per-block settings at render time

### REST API

- `GET /imedia-menu/v1/toggle-bar/{slug}` — retrieve blocks
- `POST /imedia-menu/v1/toggle-bar/{slug}` — save blocks
- `DELETE /imedia-menu/v1/toggle-bar/{slug}` — clear blocks

All requests require `edit_theme_options` capability.

### Fallback behavior

If a location has **zero configured blocks**, the default single
`.imm-mobile-toggle` button (auto-prepended via
`BlockEditorServiceProvider::maybePrependMobileToggle`) is preserved for
backward compatibility. Once the first block is saved, the toggle bar takes
over and the auto-prepended button is suppressed.

## Pro Modules (M4)

M4 ships three opt-in Pro features. They are all gated by per-location/per-item
data and are designed to be cheap to skip when not in use (assets are
conditionally enqueued via `wpdb->postmeta` queries).

### Sticky on scroll

`Sticky::attributes($settings)` returns the data-attribute map applied to each
`<nav>` element. The JS asset (`imm-sticky.js`) is enqueued in
`Sticky::enqueueScript()` (priority 120, double-enqueue guarded). The script:

1. Inserts a 1-px sentinel `<div>` before the menu.
2. Uses `IntersectionObserver` to flip a `.imm-nav--stuck` class when the
   sentinel leaves the viewport.
3. Tracks `window.scrollY` delta and toggles `.imm-nav--sticky-hidden` after
   the tolerance threshold is crossed (hide-until-scroll-up).
4. Applies the design-tab opacity as `--imm-sticky-opacity` (custom prop on
   the wrapper) and a 100vw expansion via `.imm-nav--sticky-expanded`.
5. `shouldEnable()` reads `data-sticky-desktop` / `data-sticky-mobile` to gate
   per-breakpoint behaviour; a `resize` handler re-evaluates.

Data attributes (set on `<nav>`):

| Attribute | Type | Purpose |
|---|---|---|
| `data-sticky-enabled` | `true\|false` | Master switch |
| `data-sticky-desktop` | `true\|false` | Desktop breakpoint enable |
| `data-sticky-mobile` | `true\|false` | Mobile breakpoint enable |
| `data-sticky-opacity` | `0.2..1.0` | Background opacity when stuck |
| `data-sticky-offset` | `0..500` (px) | Top offset when stuck |
| `data-sticky-expand` | `true\|false` | Expand to 100vw when stuck |
| `data-sticky-expand-mobile` | `true\|false` | Expand to 100vw on mobile when stuck |
| `data-sticky-hide` | `true\|false` | Hide-until-scroll-up enabled |
| `data-sticky-hide-tolerance` | `0..200` (px) | Scroll delta before hiding |
| `data-sticky-hide-offset` | `0..500` (px) | Top offset that triggers hide |

Per-item visibility is set via the postmeta `_imedia_menu_sticky_visibility`
∈ `{always, show-when-stuck, hide-when-stuck}` and surfaced as
`imm-sticky-{show,hide}-when-stuck` classes on the `<li>`.

Filter: `apply_filters('imm_sticky_attributes', $attrs, $settings)` —
`Sticky::attributes()` passes the assembled map through this filter before
returning.

### Menu item badges

`Badge::render($itemId)` returns the `<span>` markup for a single item.
The postmeta used is `_imedia_menu_badge_style` ∈ `{disabled, style-1, style-2, style-3, style-4}`
plus the optional `_imedia_menu_badge_hide_mobile` / `_badge_hide_desktop`
booleans. Backward compat: legacy `_badge_text` / `_badge_color` /
`_badge_text_color` / `_badge_position` postmeta are still read in
`MenuItemFields::renderFields()`.

CSS class structure:

```html
<span class="imm-badge imm-badge--style-1 imm-hide-on-mobile"
      data-style="style-1"
      style="--imm-badge-bg:#D32F2F;--imm-badge-text:#fff">
  New
</span>
```

Defaults match megamenu-pro: Red `#D32F2F` (style-1), Teal `#00796B`
(style-2), Amber `#FFC107` (style-3), Indigo `#303F9F` (style-4). Defaults
are defined in `DesignTab::register()` and can be overridden per-location via
the 8 design-tab fields (`badge_{1-4}_bg` / `badge_{1-4}_text`).

Assets (`imm-badge.css`) are conditionally enqueued in
`Assets::enqueueBadgeAssets()` — the `$wpdb->postmeta` query returns
non-`disabled` styles so the asset is only loaded on pages where badges
are actually rendered.

Filter: `apply_filters('imm_badge_html', $html, $itemId, $style)` —
`Badge::render()` passes the final HTML through this filter before
returning. `$style` is the resolved style key (`'style-1'` etc).

### Per-item style overrides

`StyleOverrides::getItemStyles($itemId)` returns the inline
`--imm-item-...` CSS custom property string to be applied to an `<li>`.
The two postmeta keys used are:

- `_imedia_menu_styles_enabled` — array of property keys the user toggled
  on (whitelisted against `StyleOverrides::PROPERTIES` keys).
- `_imedia_menu_styles_values` — assoc array of property key → value
  (sanitized via `StyleOverrides::sanitizeValue()`).

Both keys are stored as PHP `serialize()` arrays. The asset is enqueued in
`Assets::enqueueStyleOverridesAssets()` and the per-item CSS is emitted via
`Assets::buildStyleOverridesCss()` → `wp_add_inline_style('imm-base', $css)`.

The 40 properties are organised into 5 groups (Background, Font, Border, Icon,
Spacing, Panel). The full map lives in `StyleOverrides::PROPERTIES` and is the
single source of truth used by:

- The admin form (`MenuItemFields::renderStyleOverrides()`) for input
  rendering.
- The save handler (`MenuItemFields::saveStyleOverrides()`) for whitelist
  enforcement and sanitization.
- The CSS emitter (`StyleOverrides::cssVarFor()`) for the actual
  `--imm-item-...` variable name.

Filter: `apply_filters('imm_style_overrides_css', $css, $itemId)` —
`Assets::buildStyleOverridesCss()` passes the per-item CSS block through
this filter before joining the global inline-style string.

Adding a new property:

1. Add the entry to `StyleOverrides::PROPERTIES` (and `COLOR_PROPERTIES` if
   it should use the color sanitizer).
2. Add a row in `MenuItemFields::renderStyleOverrides()` matching the
   `propKey`.
3. No change to `StyleOverrides::sanitizeValue()` is needed if the property
   type (color/px/enum) is already covered.

### Filter/Action Reference (M4)

| Hook | Type | Source |
|---|---|---|
| `imm_sticky_attributes` | filter | `Sticky::attributes()` |
| `imm_badge_html` | filter | `Badge::render()` |
| `imm_style_overrides_css` | filter | `Assets::buildStyleOverridesCss()` |

## Icon Providers (M5)

M5 expands the icon system from 3 to 7 providers. Each new provider implements
the `IconProvider` contract and is registered in `IconServiceProvider::boot()`.

### Provider overview

| Provider | ID | Icons | HTML pattern |
|---|---|---|---|
| Font Awesome 5 | `fa5` | 120 curated | `<span class="imm-icon imm-icon--fa5 fas fa-{name}" aria-hidden="true"></span>` |
| Font Awesome 6 | `fa6` | 120 curated | `<span class="imm-icon imm-icon--fa6 fa-solid fa-{name}" aria-hidden="true"></span>` |
| Genericons | `genericons` | 80 curated | `<span class="imm-icon imm-icon--genericons genericon genericon-{name}" aria-hidden="true"></span>` |
| Bootstrap Icons | `bootstrap-icons` | 120 curated | `<span class="imm-icon imm-icon--bootstrap-icons bi-{name}" aria-hidden="true"></span>` |

### Provider enable toggles

Each provider has an independent enable checkbox in **Settings → Icons**. The
toggles are stored in `imedia_menu_settings[icon_providers]` and read by both
`IconServiceProvider::boot()` and `RestApiServiceProvider::getIcons()`.

### Style suffixes

FA5 and FA6 providers support prefix-based style switching in `getIcon()`:

| Prefix | FA5 class | FA6 class |
|---|---|---|
| _(none)_ | `fas fa-{name}` | `fa-solid fa-{name}` |
| `brands ` | `fab fa-{name}` | `fa-brands fa-{name}` |
| `regular ` | `far fa-{name}` | `fa-regular fa-{name}` |
| `light ` | — | `fa-light fa-{name}` |

### Adding a new icon provider

1. Create `src/Icons/Providers/MyProvider.php` implementing `IconProvider`.
2. Add the enable checkbox in `IconsTab::render()` / `validate()` / `sanitize()`.
3. Register it in `IconServiceProvider::boot()`.
4. Register it in `RestApiServiceProvider::getIcons()`.
5. Add tests in `tests/php/Unit/Icons/`.

## Google Fonts System (M5)

M5 adds a Google Fonts picker matching megamenu-pro's `fonts/google/` module.

### Architecture

```
Fonts/
├── GoogleFontsProvider.php   # Static font list + URL builder
├── FontsManager.php          # Enqueue + CRUD via settings API
FontsServiceProvider.php      # Registers wp_enqueue_scripts hook
Admin/Settings/Tabs/
└── FontsTab.php              # Admin UI (add/remove fonts, weights)
```

### GoogleFontsProvider

Static utility class (no constructor):

```php
GoogleFontsProvider::getFonts();        // array — 300+ hardcoded font names
GoogleFontsProvider::getWeights();      // [100, 200, … 900]
GoogleFontsProvider::getSubsets();      // ['latin' => 'Latin', …]
GoogleFontsProvider::getFontUrl([]);    // '' (empty)
GoogleFontsProvider::getFontUrl([       // string — fonts.googleapis.com/css2 URL
    'Open Sans' => ['weights' => [400, 700]],
]);
```

The font list is filterable via `imm_google_fonts`:
```php
add_filter('imm_google_fonts', fn($fonts) => array_merge($fonts, ['My Custom Font']));
```

The final CSS2 URL is filterable via `imm_google_fonts_url`:
```php
add_filter('imm_google_fonts_url', fn($query) => $query . '&text=Hello');
```

### FontsManager

```php
$manager = new FontsManager();
$manager->getEnabledFonts();  // array from settings
$manager->saveFonts([...]);   // writes to imedia_menu_settings[google_fonts]
$manager->enqueue();          // calls wp_enqueue_style('imm-google-fonts', …)
```

### FontsTab

Admin UI tab (id: `fonts`) registered in `SettingsRegistry::registerDefaults()`.
Validates font names against the whitelist in `GoogleFontsProvider::getFonts()`,
filters weights against the 9 allowed values, and drops inactive entries.

### Data storage

```
wp_options[imedia_menu_settings][google_fonts] = [
    'Open Sans'  => ['weights' => [400, 600, 700]],
    'Roboto'     => ['weights' => [300, 400, 500, 700]],
];
```

### Filter/Action Reference (M5)

| Hook | Type | Source |
|---|---|---|
| `imm_google_fonts` | filter | `GoogleFontsProvider::getFonts()` |
| `imm_google_fonts_url` | filter | `GoogleFontsProvider::getFontUrl()` |

## Adding a New Service Provider

1. Create `src/Providers/MyServiceProvider.php` implementing
   `IMedia\Menu\Contracts\ServiceProvider`.
2. Add it to the `$shared` array in `Plugin::boot()` if it should run in
   both admin and frontend, or to the appropriate branch array otherwise.
3. Keep `register()` side-effect-free; only `boot()` should call
   `add_action`/`add_filter`.

## Coding Standards

- All PHP files declare `strict_types=1` and are namespaced under
  `IMedia\Menu\`.
- Code style is enforced by `phpcs.xml.dist` (WordPress standard with a
  custom `wp_kses_post` allowance). Run `composer phpcbf` before
  committing.
- `phpcs.xml.dist` excludes the following rules to match the codebase's
  actual style: `WordPress.NamingConventions.ValidVariableName`,
  `WordPress.NamingConventions.ValidFunctionName`,
  `WordPress.NamingConventions.ValidHookName`, `WordPress.PHP.YodaConditions`,
  `Squiz.Commenting.{FunctionComment,ClassComment,FileComment}.Missing`. The
  pre-existing codebase uses camelCase (WPCS wants snake_case) and ships
  without docblocks. New code should match the existing style. Direct DB
  queries, short ternaries, and unescaped output are still flagged.
- Static analysis is configured by `phpstan.neon` (level 5, with
  szepeviktor/phpstan-wordpress). CI fails on any `phpstan` error.
- JS bundles follow the conventions in each sub-bundle's `webpack.config.js`.

## Local WP-Test Framework

The integration suite (`phpunit.xml.dist`) requires a configured
`wp-tests-config.php`. To bootstrap a local one:

```bash
# In a separate checkout of WordPress' develop repo
bash /path/to/wp-develop/bin/install-wp-tests.sh wordpress_test \
    root '' 127.0.0.1 latest

# Then from the plugin root
WP_TESTS_DIR=/path/to/wp-develop/tests/phpunit \
    vendor/bin/phpunit -c phpunit.xml.dist
```

The unit suite (`phpunit-unit.xml.dist`) does not require a database and
is the recommended fast-feedback loop during development.

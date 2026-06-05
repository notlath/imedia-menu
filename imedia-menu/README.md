# iMedia Menu

**Premium-grade WordPress navigation and mega menu plugin. Fully free and open-source (GPL-2.0+).**

[![Plugin Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/inventivemedia/imedia-menu)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://www.php.net/releases/)
[![WordPress Version](https://img.shields.io/badge/wp-%3E%3D6.4-%2321759B.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

---

## Overview

iMedia Menu is a powerful, accessible, and developer-friendly navigation builder for WordPress. It replaces the default menu system with a full-featured mega menu engine while remaining fully backward-compatible.

Unlike other mega menu plugins, iMedia Menu is built with:

- **Zero vendor lock-in** — fully free, no feature gating, no pro version
- **Context-aware caching** — cache keys incorporate user role, device, page, and locale so authenticated/admin users never see stale menus
- **Hybrid storage** — per-item settings in `postmeta`, panel configs in custom tables with JSON columns (avoids the N+1 meta query problem)
- **WCAG 2.1 AA** — ARIA Menubar pattern, keyboard navigation, focus management, reduced motion support
- **Vanilla JS frontend** — zero dependencies, ~3KB gzipped total

---

## Features

**Mega Menu Engine**
- 14 content block types: Menu Links, Heading, Text, Icon, Image, Banner, Gutenberg Block, Widget Area, Custom HTML, Shortcode, Post/Page Listing, Taxonomy Listing, Search Bar, Divider
- Unlimited rows and columns per panel
- Custom widths (full viewport, container, or custom)
- Multiple animation styles (none, fade, slide)

**Visibility Controls**
- 8 visibility conditions: Login State, User Role, Device Type, Page Match, Date/Time Schedule, Language, URL Parameter, PHP Callback
- "All" or "Any" evaluation mode per item
- Server-side evaluation with cache-aware keying

**Icon System**
- Dashicons (180+ icons)
- Font Awesome (70+ icons)
- Custom SVG upload with sanitization

**Developer Experience**
- 14 REST API endpoints for full CRUD
- Export/Import with JSON round-trip
- Reusable template system with theme override support
- Revision history (auto-saved, max 50 per panel)
- 30+ action/filter hooks
- Gutenberg block for Full Site Editing

**Accessibility**
- ARIA Menubar (`menubar`/`menuitem`) pattern
- Full keyboard navigation (Arrow keys, Home/End, Escape, Tab)
- Screen reader announcements via live region
- Focus management on open/close
- `prefers-reduced-motion` support
- Logical CSS properties for RTL

**Performance**
- Context-aware object cache with MD5-hashed composite keys
- Generated CSS file in uploads (no inline PHP CSS)
- Conditional asset loading (admin scripts only in admin)
- Code-split CSS (base + mobile)
- Transient fallback when object cache unavailable

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | 8.1+ |
| WordPress   | 6.4+ |
| MySQL       | 5.7+ / MariaDB 10.2+ |

---

## Installation

### Standard WordPress Install

1. Download the plugin from the [Releases page](https://github.com/inventivemedia/imedia-menu/releases).
2. Upload `imedia-menu` to `/wp-content/plugins/`.
3. Activate the plugin from **Plugins → Installed Plugins**.
4. Go to **Appearance → iMedia Menu** to configure.

### Development Install

```bash
git clone https://github.com/inventivemedia/imedia-menu.git
cd imedia-menu
composer install
npm install
```

---

## Quick Start

### Creating a Mega Menu

1. Go to **Appearance → Menus** and create or edit a menu.
2. Open the **Screen Options** panel and check **Mega Menu Settings**.
3. Click the **Mega Menu Builder** button on any menu item to configure its panel.
4. Add rows and columns, then drop in content blocks.
5. Set the menu's display location under **Manage Locations**.

### Configuring Visibility

Each menu item and content block supports visibility rules:

1. In the menu editor, expand a menu item.
2. Under **Visibility Rules**, select conditions (e.g., "Show only to logged-in users").
3. Set the evaluation mode to "Match all" or "Match any" conditions.

### Using a Reusable Template

1. Go to **Appearance → iMedia Menu → Templates**.
2. Create a new template with your panel layout.
3. In the menu editor, select the template from the **Mega Panel** settings.

---

## Architecture

```
imedia-menu/
├── src/
│   ├── Activator.php              # Activation: table creation, defaults, requirements
│   ├── Deactivator.php            # Deactivation: cache cleanup, rewrite flush
│   ├── Plugin.php                 # Singleton boot with service provider pattern
│   ├── Contracts/                 # 5 interfaces (ServiceProvider, ContentBlock, …)
│   ├── Enums/                     # 8 backed enums (TriggerType, PanelWidth, …)
│   ├── Cache/                     # Context-aware caching (CacheKeyBuilder, MenuCache, CacheInvalidator)
│   ├── ContentBlocks/             # Registry + 14 block implementations
│   ├── Database/                  # Schema (3 tables), repositories, migration runner
│   ├── Export/                    # Exporter + Importer with JSON round-trip
│   ├── Frontend/                  # MenuWalker, MegaPanelRenderer, Assets, MobileNav, Sticky
│   ├── Icons/                     # IconManager + 3 providers (Dashicons, FontAwesome, Custom SVG)
│   ├── Providers/                 # 13 service providers with strict admin/frontend separation
│   ├── Visibility/                # ConditionEvaluator + 8 condition types
│   └── ...                        # Templates, REST API routes
├── assets/
│   ├── frontend/                  # CSS (imm-base.css, imm-mobile.css) + JS (imm.js, …)
│   └── admin/                     # Admin CSS/JS
├── templates/                     # Overridable theme templates
└── tests/                         # PHPUnit, Jest, Playwright E2E
```

### Service Provider Pattern

The plugin uses a service provider architecture with strict separation between admin and frontend contexts. Providers that are only needed in the admin (e.g., `MenuEditorServiceProvider`) never boot on the frontend, and vice versa.

Boot order in `Plugin::boot()`:
1. Shared providers (all contexts) — cache, visibility, REST API, icons, templates, migrations, block editor
2. Admin-only — admin pages, menu editor, panel builder, revisions
3. Frontend-only — frontend Walker, mobile menu

### Data Storage

| Data | Storage |
|------|---------|
| Per-item settings (icon, badge, visibility) | `postmeta` |
| Panel layouts (rows, columns, blocks) | Custom table with JSON column |
| Reusable templates | Custom table with JSON column |
| Revisions | Custom table with JSON column |

---

## Hooks

### Actions

| Hook | Description |
|------|-------------|
| `imedia_menu_loaded` | Fired after the plugin is fully booted |
| `imedia_menu_panel_saved` | Fired when a panel is saved; receives panel ID |
| `imedia_menu_cache_flushed` | Fired when menu cache is flushed |
| `imedia_menu_template_saved` | Fired when a template is saved; receives template ID |

### Filters

| Filter | Description |
|--------|-------------|
| `imedia_menu_walker_args` | Modify arguments passed to `wp_nav_menu()` |
| `imedia_menu_panel_classes` | Modify CSS classes on panel wrapper |
| `imedia_menu_block_output` | Modify rendered block output; receives output, block config, block type |
| `imedia_menu_visibility_conditions` | Register custom visibility conditions |
| `imedia_menu_cache_key_components` | Modify cache key components before hashing |
| `imedia_menu_search_form_html` | Modify the search block form HTML |
| `imedia_menu_mobile_breakpoint` | Override the mobile breakpoint (default: `768px`) |
| `imedia_menu_trigger_types` | Register custom trigger types |
| `imedia_menu_icon_providers` | Register custom icon providers |

---

## REST API

The plugin exposes 14 endpoints under `wp-json/imedia-menu/v1/`:

| Route | Methods | Description |
|-------|---------|-------------|
| `/menus` | GET | List all registered menus |
| `/menus/(?P<id>\d+)/items` | GET | Get items for a menu |
| `/panels/(?P<id>\d+)` | GET | Get panel for a menu item |
| `/panels/(?P<id>\d+)` | POST | Save panel for a menu item |
| `/templates` | GET | List reusable templates |
| `/templates` | POST | Create a template |
| `/templates/(?P<id>\d+)` | GET | Get a template |
| `/templates/(?P<id>\d+)` | PUT | Update a template |
| `/templates/(?P<id>\d+)` | DELETE | Delete a template |
| `/revisions/(?P<panel_id>\d+)` | GET | Get revision history for a panel |
| `/revisions/(?P<panel_id>\d+)/(?P<revision_id>\d+)` | GET | Get a specific revision |
| `/revisions/(?P<panel_id>\d+)/(?P<revision_id>\d+)/restore` | POST | Restore a revision |
| `/settings` | GET | Get plugin settings |
| `/settings` | POST | Save plugin settings |
| `/cache` | POST | Flush all menu caches |

All endpoints require `manage_options` capability and use nonce verification.

---

## Extending

### Custom Content Block

Create a class implementing `IMedia\Menu\Contracts\ContentBlock`:

```php
use IMedia\Menu\Contracts\ContentBlock;

class MyCustomBlock implements ContentBlock
{
    public function type(): string
    {
        return 'my_block';
    }

    public function title(): string
    {
        return __('My Custom Block', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        return '<div class="my-block">' . esc_html($config['text'] ?? '') . '</div>';
    }

    public function defaultConfig(): array
    {
        return ['text' => ''];
    }
}
```

Register it via the `imedia_menu_content_blocks` filter or by calling the registry directly.

### Custom Visibility Condition

Create a class implementing `IMedia\Menu\Contracts\VisibilityCondition`:

```php
use IMedia\Menu\Contracts\VisibilityCondition;

class MyCondition implements VisibilityCondition
{
    public function type(): string
    {
        return 'my_condition';
    }

    public function label(): string
    {
        return __('My Custom Condition', 'imedia-menu');
    }

    public function evaluate(array $config): bool
    {
        return !empty($config['my_value']);
    }
}
```

Register it via the `imedia_menu_visibility_conditions` filter.

### Theme Override

Copy template files from `wp-content/plugins/imedia-menu/templates/` to `your-theme/imedia-menu/` and they will be loaded automatically.

Available templates:
- `menu-wrapper.php` — Main menu container markup
- `mega-panel.php` — Mega panel layout (rows, columns, blocks)
- `mobile-nav.php` — Mobile off-canvas navigation

---

## Testing

### PHPUnit (Standalone)

```bash
composer install
vendor/bin/phpunit -c phpunit-unit.xml.dist
```

111 unit tests across visibility conditions, cache, content blocks, enums, icons, export/import, and plugin bootstrap.

### PHPUnit (Integration)

Requires WordPress PHPUnit test suite:

```bash
WP_TESTS_DIR=/path/to/wordpress-tests-lib vendor/bin/phpunit
```

### Jest (Frontend JS)

```bash
npm install
npx jest
```

15 tests across the frontend JS modules and admin editor JS.

### Playwright E2E

Requires a running WordPress site:

```bash
WP_HOME=http://my-site.local npx playwright test
```

---

## Contributing

1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/my-feature`.
3. Commit your changes: `git commit -am 'Add my feature'`.
4. Push to the branch: `git push origin feature/my-feature`.
5. Open a pull request.

Please run all tests before submitting:

```bash
composer test           # PHPCS + PHPStan + PHPUnit
npx jest                # JS tests
```

---

## License

**iMedia Menu** — Copyright (c) Inventive Media

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

---

Built with care by [Inventive Media](https://inventivemedia.com).

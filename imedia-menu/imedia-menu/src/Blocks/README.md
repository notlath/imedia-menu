# src/Blocks/

**Status:** ✅ Done — Milestone 8 complete.

This directory holds the PHP source for the navigation block.

## Files

| File | Purpose |
|------|---------|
| `Navigation/Navigation.php` | Registers the `imedia-menu/navigation` block via `register_block_type()` and servers as the render callback. Uses `MenuWalker` with location-aware settings merging, inline CSS, and mobile toggle prepend. |
| `Navigation/EditorPreview.php` | Editor-side helpers: menu listing with assigned locations, preview HTML rendering. |
| `render.php` | Co-located at `assets/blocks/navigation-block/render.php` — thin wrapper that delegates to `Navigation::render()`. Referenced via `"render": "file:./render.php"` in `block.json`. |

## Architecture

The block follows the WordPress 6.1+ self-contained block pattern:

1. `block.json` declares metadata, attributes, supports, editor script/style, frontend style, and a file-based render callback.
2. `render.php` delegates to `Navigation::render()` which handles settings resolution, location detection, asset enqueuing, walker construction, and HTML generation.
3. The JS source (`src/edit.js`, `src/index.js`) handles the editor UI — menu selection via REST API and `ServerSideRender` preview.

## Registration

Registered via `BlockEditorServiceProvider::boot()` → `Navigation::register()` on the `init` hook.

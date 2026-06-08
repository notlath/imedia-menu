# src/RestApi/

**Status:** Placeholder. Implementation lands in Milestone 8 (M8) of the implementation plan.

This directory will hold the PHP controller classes for the
`imedia-menu/v1/*` REST routes registered by `src/Providers/RestApiServiceProvider.php`.
Today that provider registers 18+ routes that have no handler classes — they currently
fail silently with `rest_no_route` errors.

## What goes here

```
src/RestApi/
  Controllers/
    PanelController.php        # GET/POST /panels, /panels/{id}
    TemplateController.php     # GET/POST /templates
    SettingsController.php     # GET/PUT /settings
    ExportController.php       # GET /export, POST /import
    IconController.php         # GET /icons
    FontController.php         # GET /fonts
    CacheController.php        # DELETE /cache
```

## Why empty

- `RestApiServiceProvider.php` (21 KB) registers 18+ routes
- The actual route handlers were never written
- M8 will populate this directory with the controllers that satisfy those routes

## See also

- `../Blocks/README.md` — the parallel placeholder for the navigation block
- `../Providers/RestApiServiceProvider.php` — registers the routes
- `../../../AUDIT.md` section 5 — open-source readiness blockers item #3

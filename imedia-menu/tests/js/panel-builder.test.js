/**
 * Panel-builder block registry parity test.
 *
 * Asserts that every PHP BlockType enum case has a matching entry in:
 *   - assets/admin/panel-builder/src/utils/block-registry.js (BLOCK_TYPES)
 *   - assets/admin/panel-builder/src/utils/default-configs.js (DEFAULT_BLOCK_CONFIGS)
 *   - assets/admin/panel-builder/src/components/Settings/BlockSettingsPanel.js (SETTINGS_MAP)
 *
 * Uses fs.readFileSync + regex extraction so it works without a webpack build.
 */
const fs = require('fs');
const path = require('path');

const PLUGIN_ROOT = path.resolve(__dirname, '..', '..');
const BLOCK_REGISTRY_JS = path.join(
    PLUGIN_ROOT,
    'assets/admin/panel-builder/src/utils/block-registry.js'
);
const DEFAULT_CONFIGS_JS = path.join(
    PLUGIN_ROOT,
    'assets/admin/panel-builder/src/utils/default-configs.js'
);
const SETTINGS_PANEL_JS = path.join(
    PLUGIN_ROOT,
    'assets/admin/panel-builder/src/components/Settings/BlockSettingsPanel.js'
);
const PHP_ENUM = path.join(PLUGIN_ROOT, 'src/Enums/BlockType.php');

function readFile(p) {
    return fs.readFileSync(p, 'utf8');
}

function extractPhpEnumValues(src) {
    const re = /case\s+([A-Za-z_][A-Za-z0-9_]*)\s*=\s*'([^']+)'/g;
    const values = [];
    let m;
    while ((m = re.exec(src)) !== null) {
        values.push(m[2]);
    }
    return values;
}

function extractJsSlugsFromBlockRegistry(src) {
    const re = /type:\s*'([a-z_]+)'/g;
    const values = [];
    let m;
    while ((m = re.exec(src)) !== null) {
        values.push(m[1]);
    }
    return values;
}

function extractJsSlugsFromDefaultConfigs(src) {
    const re = /^\s*([a-z_]+):\s*\{/gm;
    const values = [];
    let m;
    while ((m = re.exec(src)) !== null) {
        values.push(m[1]);
    }
    return values;
}

function extractJsSlugsFromSettingsMap(src) {
    const mapMatch = src.match(/const\s+SETTINGS_MAP\s*=\s*\{([\s\S]*?)\n\};/);
    if (!mapMatch) {
        return [];
    }
    const body = mapMatch[1];
    const re = /^\s*([a-z_]+):\s*([A-Z][A-Za-z]+)/gm;
    const values = [];
    let m;
    while ((m = re.exec(body)) !== null) {
        values.push(m[1]);
    }
    return values;
}

describe('panel-builder block registry parity', () => {
    const enumSrc = readFile(PHP_ENUM);
    const phpValues = extractPhpEnumValues(enumSrc);

    const blockRegistrySrc = readFile(BLOCK_REGISTRY_JS);
    const defaultConfigsSrc = readFile(DEFAULT_CONFIGS_JS);
    const settingsPanelSrc = readFile(SETTINGS_PANEL_JS);

    const registrySlugs = extractJsSlugsFromBlockRegistry(blockRegistrySrc);
    const configSlugs = extractJsSlugsFromDefaultConfigs(defaultConfigsSrc);
    const settingsSlugs = extractJsSlugsFromSettingsMap(settingsPanelSrc);

    test('PHP BlockType has 21 cases', () => {
        expect(phpValues.length).toBe(21);
    });

    test('every PHP enum value is in BLOCK_TYPES', () => {
        for (const v of phpValues) {
            expect(registrySlugs).toContain(v);
        }
    });

    test('every PHP enum value has a DEFAULT_BLOCK_CONFIGS entry', () => {
        for (const v of phpValues) {
            expect(configSlugs).toContain(v);
        }
    });

    test('every PHP enum value has a SETTINGS_MAP entry', () => {
        for (const v of phpValues) {
            expect(settingsSlugs).toContain(v);
        }
    });

    test('BLOCK_TYPES has no extra slugs not in the PHP enum', () => {
        for (const v of registrySlugs) {
            expect(phpValues).toContain(v);
        }
    });

    test('the 7 new M1 blocks are present', () => {
        const newBlocks = [
            'real_widget',
            'replacements',
            'tabbed',
            'accordion',
            'login_state',
            'cart',
            'dynamic_html',
        ];
        for (const b of newBlocks) {
            expect(phpValues).toContain(b);
            expect(registrySlugs).toContain(b);
            expect(configSlugs).toContain(b);
            expect(settingsSlugs).toContain(b);
        }
    });

    test('M2 enums exist in PHP', () => {
        const enums = ['PanelLayoutType', 'MenuOrientation', 'OverlayMode'];
        for (const name of enums) {
            const php = readFile(path.join(PLUGIN_ROOT, 'src/Enums', name + '.php'));
            const m = php.match(/enum\s+(\w+)\s*:\s*string/);
            expect(m).not.toBeNull();
            expect(m[1]).toBe(name);
        }
    });

    test('M2 PanelSettingsDrawer has layout_type selector', () => {
        const drawerSrc = readFile(
            path.join(PLUGIN_ROOT, 'assets/admin/panel-builder/src/components/PanelSettings/PanelSettingsDrawer.js')
        );
        expect(drawerSrc).toMatch(/layout_type/);
        expect(drawerSrc).toMatch(/LAYOUT_OPTIONS/);
    });

    test('M2 BlockPicker has flyout notice', () => {
        const pickerSrc = readFile(
            path.join(PLUGIN_ROOT, 'assets/admin/panel-builder/src/components/BlockPicker/BlockPicker.js')
        );
        expect(pickerSrc).toMatch(/isFlyout/);
        expect(pickerSrc).toMatch(/imm-block-picker-flyout-notice/);
    });

    test('M2 Column has span editor for grid', () => {
        const columnSrc = readFile(
            path.join(PLUGIN_ROOT, 'assets/admin/panel-builder/src/components/Column/Column.js')
        );
        expect(columnSrc).toMatch(/isGrid/);
        expect(columnSrc).toMatch(/handleSpanChange/);
    });
});

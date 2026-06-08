/**
 * Toggle Bar Designer - structural tests.
 * Validates the component file exists, imports work, and basic shape is correct.
 * Full render tests require @testing-library/react which is not in this project.
 */

const fs = require('fs');
const path = require('path');

const DESIGNER_PATH = path.resolve(
    __dirname,
    '..',
    '..',
    'assets/admin/settings-page/src/components/ToggleBarDesigner.js'
);

const API_PATH = path.resolve(
    __dirname,
    '..',
    '..',
    'assets/admin/settings-page/src/api/toggle-bar.js'
);

describe('ToggleBarDesigner', () => {
    test('component file exists', () => {
        expect(fs.existsSync(DESIGNER_PATH)).toBe(true);
    });

    test('api client file exists', () => {
        expect(fs.existsSync(API_PATH)).toBe(true);
    });

    test('component imports 8 block types', () => {
        const source = fs.readFileSync(DESIGNER_PATH, 'utf8');
        const blockTypes = [
            'menu_toggle',
            'menu_toggle_animated',
            'spacer',
            'search',
            'logo',
            'icon',
            'html',
            'custom',
        ];
        blockTypes.forEach((type) => {
            expect(source).toContain(`type: '${type}'`);
        });
    });

    test('component uses 3 region aligns', () => {
        const source = fs.readFileSync(DESIGNER_PATH, 'utf8');
        expect(source).toContain("value: 'left'");
        expect(source).toContain("value: 'center'");
        expect(source).toContain("value: 'right'");
    });

    test('api client exports fetchToggleBar', () => {
        const source = fs.readFileSync(API_PATH, 'utf8');
        expect(source).toContain('export async function fetchToggleBar');
        expect(source).toContain('export async function saveToggleBar');
        expect(source).toContain('export async function deleteToggleBar');
    });

    test('api client uses correct REST paths', () => {
        const source = fs.readFileSync(API_PATH, 'utf8');
        expect(source).toContain('/imedia-menu/v1/toggle-bar/');
    });

    test('component is wired into LocationTab', () => {
        const locationTabPath = path.resolve(
            __dirname,
            '..',
            '..',
            'assets/admin/settings-page/src/components/LocationTab.js'
        );
        const source = fs.readFileSync(locationTabPath, 'utf8');
        expect(source).toContain('ToggleBarDesigner');
        expect(source).toContain('isDesignerOpen');
    });
});

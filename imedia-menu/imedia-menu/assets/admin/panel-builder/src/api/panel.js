import apiFetch from '@wordpress/api-fetch';

export async function fetchPanel(menuItemId) {
    try {
        const response = await apiFetch({
            path: `/imedia-menu/v1/panels/${menuItemId}`,
            method: 'GET',
        });
        return {
            config: response?.config ?? null,
            styles: response?.styles ?? null,
        };
    } catch {
        return { config: null, styles: null };
    }
}

export async function savePanel(menuItemId, menuId, config, styles) {
    await apiFetch({
        path: `/imedia-menu/v1/panels/${menuItemId}`,
        method: 'POST',
        data: {
            menu_id: menuId,
            config,
            styles,
        },
    });
}

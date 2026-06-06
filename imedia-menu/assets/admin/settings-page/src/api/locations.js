import apiFetch from '@wordpress/api-fetch';

export async function fetchMenuLocations() {
    try {
        const response = await apiFetch({
            path: '/imedia-menu/v1/menus/locations',
            method: 'GET',
        });
        return Array.isArray(response) ? response : [];
    } catch {
        return [];
    }
}

export async function fetchLocationOverrides() {
    try {
        const response = await apiFetch({
            path: '/imedia-menu/v1/settings/locations',
            method: 'GET',
        });
        return response ?? {};
    } catch {
        return {};
    }
}

export async function saveLocationOverrides(slug, overrides) {
    try {
        const response = await apiFetch({
            path: `/imedia-menu/v1/settings/location/${slug}`,
            method: 'POST',
            data: overrides,
        });
        if (response?.success) {
            return true;
        }
        throw new Error('Save failed');
    } catch (error) {
        throw error;
    }
}

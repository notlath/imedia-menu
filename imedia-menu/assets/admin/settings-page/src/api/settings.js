import apiFetch from '@wordpress/api-fetch';

export async function fetchSettings() {
    try {
        const response = await apiFetch({
            path: '/imedia-menu/v1/settings',
            method: 'GET',
        });
        return response ?? {};
    } catch {
        return {};
    }
}

export async function saveSettings(settings) {
    try {
        const response = await apiFetch({
            path: '/imedia-menu/v1/settings',
            method: 'POST',
            data: settings,
        });
        if (response?.success) {
            return settings;
        }
        throw new Error('Save failed');
    } catch (error) {
        throw error;
    }
}

import apiFetch from '@wordpress/api-fetch';

export async function fetchToggleBar(slug) {
    try {
        const response = await apiFetch({
            path: `/imedia-menu/v1/toggle-bar/${slug}`,
            method: 'GET',
        });
        return response ?? { slug, blocks: [] };
    } catch {
        return { slug, blocks: [] };
    }
}

export async function saveToggleBar(slug, blocks) {
    const response = await apiFetch({
        path: `/imedia-menu/v1/toggle-bar/${slug}`,
        method: 'POST',
        data: { blocks },
    });
    if (response?.success) {
        return true;
    }
    throw new Error('Save failed');
}

export async function deleteToggleBar(slug) {
    const response = await apiFetch({
        path: `/imedia-menu/v1/toggle-bar/${slug}`,
        method: 'DELETE',
    });
    if (response?.success) {
        return true;
    }
    throw new Error('Delete failed');
}

import apiFetch from '@wordpress/api-fetch';

export async function fetchTemplates() {
    try {
        const response = await apiFetch({
            path: '/imedia-menu/v1/templates',
            method: 'GET',
        });
        return Array.isArray(response) ? response : [];
    } catch {
        return [];
    }
}

export async function createTemplate(name, description, config, styles, meta) {
    const response = await apiFetch({
        path: '/imedia-menu/v1/templates',
        method: 'POST',
        data: {
            name,
            description: description || '',
            config,
            styles,
            meta: meta || {},
        },
    });
    if (response?.id) {
        return response.id;
    }
    throw new Error('Failed to create template');
}

export async function updateTemplate(id, data) {
    const response = await apiFetch({
        path: `/imedia-menu/v1/templates/${id}`,
        method: 'PUT',
        data,
    });
    if (response?.success) {
        return true;
    }
    throw new Error('Failed to update template');
}

export async function deleteTemplate(id) {
    const response = await apiFetch({
        path: `/imedia-menu/v1/templates/${id}`,
        method: 'DELETE',
    });
    if (response?.success) {
        return true;
    }
    throw new Error('Failed to delete template');
}

export const loadSettings = (settings) => ({
    type: 'LOAD_SETTINGS',
    settings,
});

export const setLoading = (isLoading) => ({
    type: 'SET_LOADING',
    isLoading,
});

export const setSaving = (isSaving) => ({
    type: 'SET_SAVING',
    isSaving,
});

export const setDirty = (isDirty) => ({
    type: 'SET_DIRTY',
    isDirty,
});

export const setErrors = (errors) => ({
    type: 'SET_ERRORS',
    errors,
});

export const updateSettings = (updates) => ({
    type: 'UPDATE_SETTINGS',
    updates,
});

export const setActiveTab = (tab) => ({
    type: 'SET_ACTIVE_TAB',
    tab,
});

export const saveSuccess = () => ({
    type: 'SAVE_SUCCESS',
});

export const saveError = (errors) => ({
    type: 'SAVE_ERROR',
    errors,
});

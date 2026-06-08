export function getSettings(state) {
    return state.settings;
}

export function getSetting(state, key) {
    return state.settings[key];
}

export function getIsLoaded(state) {
    return state.isLoaded;
}

export function getIsLoading(state) {
    return state.isLoading;
}

export function getIsSaving(state) {
    return state.isSaving;
}

export function getIsDirty(state) {
    return state.isDirty;
}

export function getActiveTab(state) {
    return state.activeTab;
}

export function getErrors(state) {
    return state.errors;
}

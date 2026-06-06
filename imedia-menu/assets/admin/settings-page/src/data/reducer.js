import { DEFAULT_SETTINGS } from './constants';

export function createInitialState() {
    return {
        settings: { ...DEFAULT_SETTINGS },
        isLoaded: false,
        isLoading: false,
        isSaving: false,
        isDirty: false,
        activeTab: 'general',
        errors: [],
    };
}

export function reducer(state = createInitialState(), action) {
    switch (action.type) {
        case 'LOAD_SETTINGS':
            return {
                ...state,
                settings: { ...DEFAULT_SETTINGS, ...action.settings },
                isLoaded: true,
                isLoading: false,
                isDirty: false,
            };

        case 'SET_LOADING':
            return { ...state, isLoading: action.isLoading };

        case 'SET_SAVING':
            return { ...state, isSaving: action.isSaving };

        case 'SET_DIRTY':
            return { ...state, isDirty: action.isDirty };

        case 'SET_ERRORS':
            return { ...state, errors: action.errors };

        case 'UPDATE_SETTINGS':
            return {
                ...state,
                settings: { ...state.settings, ...action.updates },
                isDirty: true,
            };

        case 'SET_ACTIVE_TAB':
            return { ...state, activeTab: action.tab };

        case 'SAVE_SUCCESS':
            return {
                ...state,
                isSaving: false,
                isDirty: false,
                errors: [],
            };

        case 'SAVE_ERROR':
            return {
                ...state,
                isSaving: false,
                errors: action.errors,
            };

        default:
            return state;
    }
}

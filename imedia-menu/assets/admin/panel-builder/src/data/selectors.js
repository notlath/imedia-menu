export function getMenuItemId(state) {
    return state.menuItemId;
}

export function getMenuId(state) {
    return state.menuId;
}

export function getIsLoaded(state) {
    return state.isLoaded;
}

export function getIsLoading(state) {
    return state.isLoading;
}

export function getConfig(state) {
    return state.config;
}

export function getStyles(state) {
    return state.styles;
}

export function getRows(state) {
    return state.config.rows;
}

export function getPanelWidth(state) {
    return state.config.panel_width;
}

export function getAnimationType(state) {
    return state.config.animation_type;
}

export function getSelectedBlockId(state) {
    return state.ui.selectedBlockId;
}

export function getSelectedColumnId(state) {
    return state.ui.selectedColumnId;
}

export function getSelectedRowId(state) {
    return state.ui.selectedRowId;
}

export function getSelectedBlock(state) {
    if (!state.ui.selectedBlockId) return null;
    for (const row of state.config.rows) {
        for (const col of row.columns) {
            const block = col.blocks.find(
                (b) => b.id === state.ui.selectedBlockId
            );
            if (block) return { block, rowId: row.id, columnId: col.id };
        }
    }
    return null;
}

export function getIsDirty(state) {
    return state.ui.isDirty;
}

export function getIsSaving(state) {
    return state.ui.isSaving;
}

export function getIsBlockPickerOpen(state) {
    return state.ui.isBlockPickerOpen;
}

export function getBlockPickerTarget(state) {
    return state.ui.blockPickerTarget;
}

export function getResponsiveMode(state) {
    return state.ui.responsiveMode;
}

export function getPanelSettingsOpen(state) {
    return state.ui.panelSettingsOpen;
}

export function getErrors(state) {
    return state.ui.errors;
}

export function canUndo(state) {
    return state.undoStack.length > 0;
}

export function canRedo(state) {
    return state.redoStack.length > 0;
}

export function getTemplates(state) {
    return state.templates;
}

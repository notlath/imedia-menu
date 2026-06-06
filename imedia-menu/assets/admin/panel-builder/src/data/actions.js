export const loadPanel = (menuItemId, menuId, config, styles) => ({
    type: 'LOAD_PANEL',
    menuItemId,
    menuId,
    config,
    styles,
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

export const addRow = () => ({ type: 'ADD_ROW' });

export const removeRow = (rowId) => ({ type: 'REMOVE_ROW', rowId });

export const updateRow = (rowId, updates) => ({
    type: 'UPDATE_ROW',
    rowId,
    updates,
});

export const reorderRows = (rows) => ({ type: 'REORDER_ROWS', rows });

export const addColumn = (rowId) => ({ type: 'ADD_COLUMN', rowId });

export const removeColumn = (rowId, columnId) => ({
    type: 'REMOVE_COLUMN',
    rowId,
    columnId,
});

export const updateColumn = (rowId, columnId, updates) => ({
    type: 'UPDATE_COLUMN',
    rowId,
    columnId,
    updates,
});

export const reorderColumns = (rowId, columns) => ({
    type: 'REORDER_COLUMNS',
    rowId,
    columns,
});

export const addBlock = (rowId, columnId, blockType, defaultConfig) => ({
    type: 'ADD_BLOCK',
    rowId,
    columnId,
    blockType,
    defaultConfig,
});

export const updateBlock = (rowId, columnId, blockId, updates) => ({
    type: 'UPDATE_BLOCK',
    rowId,
    columnId,
    blockId,
    updates,
});

export const removeBlock = (rowId, columnId, blockId) => ({
    type: 'REMOVE_BLOCK',
    rowId,
    columnId,
    blockId,
});

export const reorderBlocks = (rowId, columnId, blocks) => ({
    type: 'REORDER_BLOCKS',
    rowId,
    columnId,
    blocks,
});

export const updatePanelConfig = (updates) => ({
    type: 'UPDATE_PANEL_CONFIG',
    updates,
});

export const updatePanelStyles = (updates) => ({
    type: 'UPDATE_PANEL_STYLES',
    updates,
});

export const setSelection = (selectedBlockId, selectedColumnId, selectedRowId) => ({
    type: 'SET_SELECTION',
    selectedBlockId,
    selectedColumnId,
    selectedRowId,
});

export const clearSelection = () => ({ type: 'CLEAR_SELECTION' });

export const openBlockPicker = (target) => ({
    type: 'OPEN_BLOCK_PICKER',
    target,
});

export const closeBlockPicker = () => ({ type: 'CLOSE_BLOCK_PICKER' });

export const setResponsiveMode = (mode) => ({
    type: 'SET_RESPONSIVE_MODE',
    mode,
});

export const togglePanelSettings = () => ({ type: 'TOGGLE_PANEL_SETTINGS' });

export const undo = () => ({ type: 'UNDO' });

export const redo = () => ({ type: 'REDO' });

export const setTemplates = (templates) => ({
    type: 'SET_TEMPLATES',
    templates,
});

export const applyTemplate = (config, styles) => ({
    type: 'APPLY_TEMPLATE',
    config,
    styles,
});

export const resetPanel = () => ({
    type: 'RESET_PANEL',
});

import { v4 as uuid } from 'uuid';
import { DEFAULT_CONFIG, DEFAULT_STYLES } from './constants';
import { takeSnapshot } from '../utils/undo-redo';

function generateId() {
    return uuid();
}

function createDefaultRow() {
    return {
        id: generateId(),
        styles: { background: '', padding: {} },
        columns: [
            {
                id: generateId(),
                width: 'auto',
                styles: { padding: {} },
                blocks: [],
            },
        ],
    };
}

export function createInitialState() {
    return {
        menuItemId: null,
        menuId: null,
        isLoaded: false,
        isLoading: false,
        config: { ...DEFAULT_CONFIG },
        styles: { ...DEFAULT_STYLES },
        templates: [],
        ui: {
            selectedBlockId: null,
            selectedColumnId: null,
            selectedRowId: null,
            isDirty: false,
            isSaving: false,
            isBlockPickerOpen: false,
            blockPickerTarget: null,
            responsiveMode: 'desktop',
            panelSettingsOpen: false,
            errors: [],
        },
        undoStack: [],
        redoStack: [],
    };
}

export function reducer(state = createInitialState(), action) {
    switch (action.type) {
        case 'LOAD_PANEL':
            return takeSnapshot({
                ...state,
                menuItemId: action.menuItemId,
                menuId: action.menuId,
                isLoaded: true,
                isLoading: false,
                config: {
                    ...DEFAULT_CONFIG,
                    ...action.config,
                    rows: action.config?.rows?.length
                        ? action.config.rows
                        : [createDefaultRow()],
                },
                styles: { ...DEFAULT_STYLES, ...(action.styles || {}) },
            });

        case 'SET_LOADING':
            return { ...state, isLoading: action.isLoading };

        case 'SET_ERRORS':
            return {
                ...state,
                ui: { ...state.ui, errors: action.errors },
            };

        case 'ADD_ROW': {
            const newRow = createDefaultRow();
            const next = {
                ...state,
                config: {
                    ...state.config,
                    rows: [...state.config.rows, newRow],
                },
                ui: { ...state.ui, isDirty: true },
            };
            return takeSnapshot(next);
        }

        case 'REMOVE_ROW': {
            const rows = state.config.rows.filter((r) => r.id !== action.rowId);
            const next = {
                ...state,
                config: { ...state.config, rows },
                ui: {
                    ...state.ui,
                    isDirty: true,
                    selectedRowId:
                        state.ui.selectedRowId === action.rowId
                            ? null
                            : state.ui.selectedRowId,
                    selectedColumnId:
                        state.ui.selectedColumnId &&
                        !rows.some((r) =>
                            r.columns.some((c) => c.id === state.ui.selectedColumnId)
                        )
                            ? null
                            : state.ui.selectedColumnId,
                },
            };
            return takeSnapshot(next);
        }

        case 'UPDATE_ROW': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId ? { ...r, ...action.updates } : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: { ...state.ui, isDirty: true },
            });
        }

        case 'REORDER_ROWS':
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows: action.rows },
                ui: { ...state.ui, isDirty: true },
            });

        case 'ADD_COLUMN': {
            const newCol = {
                id: generateId(),
                width: 'auto',
                styles: { padding: {} },
                blocks: [],
            };
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? { ...r, columns: [...r.columns, newCol] }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: { ...state.ui, isDirty: true },
            });
        }

        case 'REMOVE_COLUMN': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? {
                          ...r,
                          columns: r.columns.filter(
                              (c) => c.id !== action.columnId
                          ),
                      }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: {
                    ...state.ui,
                    isDirty: true,
                    selectedColumnId:
                        state.ui.selectedColumnId === action.columnId
                            ? null
                            : state.ui.selectedColumnId,
                },
            });
        }

        case 'UPDATE_COLUMN': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? {
                          ...r,
                          columns: r.columns.map((c) =>
                              c.id === action.columnId
                                  ? { ...c, ...action.updates }
                                  : c
                          ),
                      }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: { ...state.ui, isDirty: true },
            });
        }

        case 'REORDER_COLUMNS': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? { ...r, columns: action.columns }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: { ...state.ui, isDirty: true },
            });
        }

        case 'ADD_BLOCK': {
            const newBlock = {
                id: generateId(),
                type: action.blockType,
                config: action.defaultConfig || {},
                styles: {},
                visibility: {
                    mode: 'all',
                    conditions: [],
                },
            };
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? {
                          ...r,
                          columns: r.columns.map((c) =>
                              c.id === action.columnId
                                  ? { ...c, blocks: [...c.blocks, newBlock] }
                                  : c
                          ),
                      }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: {
                    ...state.ui,
                    isDirty: true,
                    isBlockPickerOpen: false,
                    blockPickerTarget: null,
                    selectedBlockId: newBlock.id,
                    selectedColumnId: action.columnId,
                },
            });
        }

        case 'UPDATE_BLOCK': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? {
                          ...r,
                          columns: r.columns.map((c) =>
                              c.id === action.columnId
                                  ? {
                                        ...c,
                                        blocks: c.blocks.map((b) =>
                                            b.id === action.blockId
                                                ? { ...b, ...action.updates }
                                                : b
                                        ),
                                    }
                                  : c
                          ),
                      }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: { ...state.ui, isDirty: true },
            });
        }

        case 'REMOVE_BLOCK': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? {
                          ...r,
                          columns: r.columns.map((c) =>
                              c.id === action.columnId
                                  ? {
                                        ...c,
                                        blocks: c.blocks.filter(
                                            (b) => b.id !== action.blockId
                                        ),
                                    }
                                  : c
                          ),
                      }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: {
                    ...state.ui,
                    isDirty: true,
                    selectedBlockId:
                        state.ui.selectedBlockId === action.blockId
                            ? null
                            : state.ui.selectedBlockId,
                },
            });
        }

        case 'REORDER_BLOCKS': {
            const rows = state.config.rows.map((r) =>
                r.id === action.rowId
                    ? {
                          ...r,
                          columns: r.columns.map((c) =>
                              c.id === action.columnId
                                  ? { ...c, blocks: action.blocks }
                                  : c
                          ),
                      }
                    : r
            );
            return takeSnapshot({
                ...state,
                config: { ...state.config, rows },
                ui: { ...state.ui, isDirty: true },
            });
        }

        case 'UPDATE_PANEL_CONFIG':
            return takeSnapshot({
                ...state,
                config: { ...state.config, ...action.updates },
                ui: { ...state.ui, isDirty: true },
            });

        case 'UPDATE_PANEL_STYLES':
            return takeSnapshot({
                ...state,
                styles: { ...state.styles, ...action.updates },
                ui: { ...state.ui, isDirty: true },
            });

        case 'SET_SELECTION':
            return {
                ...state,
                ui: {
                    ...state.ui,
                    selectedBlockId: action.selectedBlockId ?? null,
                    selectedColumnId: action.selectedColumnId ?? null,
                    selectedRowId: action.selectedRowId ?? null,
                },
            };

        case 'CLEAR_SELECTION':
            return {
                ...state,
                ui: {
                    ...state.ui,
                    selectedBlockId: null,
                    selectedColumnId: null,
                    selectedRowId: null,
                },
            };

        case 'OPEN_BLOCK_PICKER':
            return {
                ...state,
                ui: {
                    ...state.ui,
                    isBlockPickerOpen: true,
                    blockPickerTarget: action.target,
                },
            };

        case 'CLOSE_BLOCK_PICKER':
            return {
                ...state,
                ui: {
                    ...state.ui,
                    isBlockPickerOpen: false,
                    blockPickerTarget: null,
                },
            };

        case 'SET_RESPONSIVE_MODE':
            return {
                ...state,
                ui: { ...state.ui, responsiveMode: action.mode },
            };

        case 'TOGGLE_PANEL_SETTINGS':
            return {
                ...state,
                ui: {
                    ...state.ui,
                    panelSettingsOpen: !state.ui.panelSettingsOpen,
                },
            };

        case 'SET_SAVING':
            return {
                ...state,
                ui: { ...state.ui, isSaving: action.isSaving },
            };

        case 'SET_DIRTY':
            return {
                ...state,
                ui: { ...state.ui, isDirty: action.isDirty },
            };

        case 'UNDO': {
            if (state.undoStack.length === 0) return state;
            const prev = state.undoStack[state.undoStack.length - 1];
            return {
                ...state,
                ...prev,
                undoStack: state.undoStack.slice(0, -1),
                redoStack: [
                    ...state.redoStack,
                    {
                        config: state.config,
                        styles: state.styles,
                    },
                ],
                ui: { ...state.ui, isDirty: true },
            };
        }

        case 'REDO': {
            if (state.redoStack.length === 0) return state;
            const next = state.redoStack[state.redoStack.length - 1];
            return {
                ...state,
                ...next,
                redoStack: state.redoStack.slice(0, -1),
                undoStack: [
                    ...state.undoStack,
                    {
                        config: state.config,
                        styles: state.styles,
                    },
                ],
                ui: { ...state.ui, isDirty: true },
            };
        }

        case 'SET_TEMPLATES':
            return {
                ...state,
                templates: action.templates,
            };

        case 'APPLY_TEMPLATE': {
            const replacementConfig = {
                ...state.config,
                ...action.config,
            };
            const replacementStyles = {
                ...(state.styles || {}),
                ...(action.styles || {}),
            };
            return takeSnapshot({
                ...state,
                config: replacementConfig,
                styles: replacementStyles,
                ui: {
                    ...state.ui,
                    isDirty: true,
                },
            });
        }

        case 'RESET_PANEL':
            return createInitialState();

        default:
            return state;
    }
}

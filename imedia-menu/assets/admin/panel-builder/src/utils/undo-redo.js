const MAX_HISTORY = 50;

export function takeSnapshot(state) {
    const snapshot = {
        config: JSON.parse(JSON.stringify(state.config)),
        styles: JSON.parse(JSON.stringify(state.styles)),
    };

    const undoStack = state.undoStack || [];
    const newStack = [...undoStack, snapshot];

    if (newStack.length > MAX_HISTORY) {
        newStack.shift();
    }

    return {
        ...state,
        undoStack: newStack,
        redoStack: [],
    };
}

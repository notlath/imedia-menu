export function validateConfig(config) {
    const errors = [];

    if (!config) {
        errors.push('Config is required');
        return errors;
    }

    if (!Array.isArray(config.rows)) {
        errors.push('Config must contain a rows array');
        return errors;
    }

    config.rows.forEach((row, rowIdx) => {
        if (!row.id) {
            errors.push(`Row ${rowIdx} is missing an id`);
        }
        if (!Array.isArray(row.columns)) {
            errors.push(`Row ${rowIdx} is missing columns`);
            return;
        }
        row.columns.forEach((col, colIdx) => {
            if (!col.id) {
                errors.push(`Row ${rowIdx}, column ${colIdx} is missing an id`);
            }
            if (!Array.isArray(col.blocks)) {
                errors.push(
                    `Row ${rowIdx}, column ${colIdx} is missing blocks`
                );
            }
        });
    });

    return errors;
}

const VALID_ANIMATIONS = ['fade', 'slide', 'none'];
const VALID_WIDTHS = ['container', 'full', 'custom'];

export function validatePanelMeta(meta) {
    const errors = [];
    if (meta.animation_type && !VALID_ANIMATIONS.includes(meta.animation_type)) {
        errors.push(`Invalid animation type: ${meta.animation_type}`);
    }
    if (meta.panel_width && !VALID_WIDTHS.includes(meta.panel_width)) {
        errors.push(`Invalid panel width: ${meta.panel_width}`);
    }
    return errors;
}

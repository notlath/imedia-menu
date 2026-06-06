import { __ } from '@wordpress/i18n';
import { SelectControl, Button, TextControl } from '@wordpress/components';

const MODE_OPTIONS = [
    { label: __('All Conditions Must Match', 'imedia-menu'), value: 'all' },
    { label: __('Any Condition Must Match', 'imedia-menu'), value: 'any' },
];

const CONDITION_TYPES = [
    { label: __('Login Status', 'imedia-menu'), value: 'login_state' },
    { label: __('User Role', 'imedia-menu'), value: 'user_role' },
    { label: __('Device Type', 'imedia-menu'), value: 'device_type' },
    { label: __('Page', 'imedia-menu'), value: 'page' },
    { label: __('Schedule', 'imedia-menu'), value: 'schedule' },
    { label: __('Language', 'imedia-menu'), value: 'language' },
    { label: __('URL Parameter', 'imedia-menu'), value: 'url_parameter' },
    { label: __('PHP Callback', 'imedia-menu'), value: 'php_callback' },
];

function ConditionEditor({ condition, index, onChange }) {
    const update = (key, value) => {
        const updated = { ...condition, [key]: value };
        onChange(index, updated);
    };

    switch (condition.type) {
        case 'login_state':
            return (
                <SelectControl
                    label={__('State', 'imedia-menu')}
                    value={condition.value || ''}
                    options={[
                        { label: __('Logged In', 'imedia-menu'), value: 'logged_in' },
                        { label: __('Logged Out', 'imedia-menu'), value: 'logged_out' },
                    ]}
                    onChange={(value) => update('value', value)}
                />
            );

        case 'user_role':
            return (
                <TextControl
                    label={__('Role (comma-separated)', 'imedia-menu')}
                    value={condition.value || ''}
                    onChange={(value) => update('value', value)}
                />
            );

        case 'device_type':
            return (
                <SelectControl
                    label={__('Device', 'imedia-menu')}
                    value={condition.value || ''}
                    options={[
                        { label: __('Desktop', 'imedia-menu'), value: 'desktop' },
                        { label: __('Tablet', 'imedia-menu'), value: 'tablet' },
                        { label: __('Mobile', 'imedia-menu'), value: 'mobile' },
                    ]}
                    onChange={(value) => update('value', value)}
                />
            );

        case 'page':
            return (
                <TextControl
                    label={__('Page IDs (comma-separated)', 'imedia-menu')}
                    value={condition.value || ''}
                    onChange={(value) => update('value', value)}
                />
            );

        case 'schedule':
            return (
                <>
                    <TextControl
                        label={__('Start Date (Y-m-d)', 'imedia-menu')}
                        type="date"
                        value={condition.start || ''}
                        onChange={(value) => update('start', value)}
                    />
                    <TextControl
                        label={__('End Date (Y-m-d)', 'imedia-menu')}
                        type="date"
                        value={condition.end || ''}
                        onChange={(value) => update('end', value)}
                    />
                </>
            );

        case 'language':
            return (
                <TextControl
                    label={__('Language Code', 'imedia-menu')}
                    help={__('e.g. en, fr, de', 'imedia-menu')}
                    value={condition.value || ''}
                    onChange={(value) => update('value', value)}
                />
            );

        case 'url_parameter':
            return (
                <>
                    <SelectControl
                        label={__('Mode', 'imedia-menu')}
                        value={condition.mode || 'equals'}
                        options={[
                            { label: __('Equals', 'imedia-menu'), value: 'equals' },
                            { label: __('Not Equals', 'imedia-menu'), value: 'not_equals' },
                            { label: __('Exists', 'imedia-menu'), value: 'exists' },
                            { label: __('Not Exists', 'imedia-menu'), value: 'not_exists' },
                            { label: __('Contains', 'imedia-menu'), value: 'contains' },
                            { label: __('Regex', 'imedia-menu'), value: 'regex' },
                        ]}
                        onChange={(value) => update('mode', value)}
                    />
                    <TextControl
                        label={__('Parameter Key', 'imedia-menu')}
                        value={condition.key || ''}
                        onChange={(value) => update('key', value)}
                    />
                    {!['exists', 'not_exists'].includes(condition.mode) && (
                        <TextControl
                            label={__('Parameter Value', 'imedia-menu')}
                            value={condition.value || ''}
                            onChange={(value) => update('value', value)}
                        />
                    )}
                </>
            );

        case 'php_callback':
            return (
                <TextControl
                    label={__('Callback Function', 'imedia-menu')}
                    help={__('A PHP function that returns a boolean.', 'imedia-menu')}
                    value={condition.value || ''}
                    onChange={(value) => update('value', value)}
                />
            );

        default:
            return null;
    }
}

export default function VisibilitySettings({ visibility, onChange }) {
    const conditions = visibility?.conditions || [];
    const mode = visibility?.mode || 'all';

    const addCondition = (type) => {
        const newCondition = { type, value: '' };
        if (type === 'schedule') {
            newCondition.start = '';
            newCondition.end = '';
        }
        if (type === 'url_parameter') {
            newCondition.mode = 'equals';
            newCondition.key = '';
            newCondition.value = '';
        }
        onChange({
            mode,
            conditions: [...conditions, newCondition],
        });
    };

    const updateCondition = (index, updated) => {
        const updatedConditions = conditions.map((c, i) =>
            i === index ? updated : c
        );
        onChange({ mode, conditions: updatedConditions });
    };

    const removeCondition = (index) => {
        onChange({
            mode,
            conditions: conditions.filter((_, i) => i !== index),
        });
    };

    return (
        <div className="imm-visibility-settings">
            <p className="imm-field-description">
                {__('Control when this block is visible to visitors.', 'imedia-menu')}
            </p>
            <SelectControl
                label={__('Match Mode', 'imedia-menu')}
                value={mode}
                options={MODE_OPTIONS}
                onChange={(value) =>
                    onChange({ mode: value, conditions })
                }
            />

            {conditions.map((condition, index) => (
                <div key={index} className="imm-condition-item">
                    <div className="imm-condition-header">
                        <SelectControl
                            value={condition.type}
                            options={CONDITION_TYPES}
                            onChange={(value) => {
                                const reset = { type: value, value: '' };
                                if (value === 'schedule') {
                                    reset.start = '';
                                    reset.end = '';
                                }
                                if (value === 'url_parameter') {
                                    reset.mode = 'equals';
                                    reset.key = '';
                                    reset.value = '';
                                }
                                updateCondition(index, reset);
                            }}
                        />
                        <Button
                            icon="trash"
                            label={__('Remove', 'imedia-menu')}
                            onClick={() => removeCondition(index)}
                            isDestructive
                            size="small"
                        />
                    </div>
                    <ConditionEditor
                        condition={condition}
                        index={index}
                        onChange={updateCondition}
                    />
                </div>
            ))}

            <div className="imm-add-condition">
                <SelectControl
                    label={__('Add Condition', 'imedia-menu')}
                    value=""
                    options={[
                        { label: __('Select condition type...', 'imedia-menu'), value: '' },
                        ...CONDITION_TYPES,
                    ]}
                    onChange={(value) => {
                        if (value) addCondition(value);
                    }}
                />
            </div>
        </div>
    );
}

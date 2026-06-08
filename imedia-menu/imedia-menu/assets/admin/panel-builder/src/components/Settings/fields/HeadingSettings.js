import { __ } from '@wordpress/i18n';
import { SelectControl, TextControl } from '@wordpress/components';

const LEVEL_OPTIONS = [
    { label: 'H2', value: 'h2' },
    { label: 'H3', value: 'h3' },
    { label: 'H4', value: 'h4' },
    { label: 'H5', value: 'h5' },
    { label: 'H6', value: 'h6' },
    { label: 'Span', value: 'span' },
    { label: 'Div', value: 'div' },
];

export default function HeadingSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Text', 'imedia-menu')}
                value={config.text || ''}
                onChange={(value) => onChange({ config: { ...config, text: value } })}
            />
            <SelectControl
                label={__('Level', 'imedia-menu')}
                value={config.level || 'h3'}
                options={LEVEL_OPTIONS}
                onChange={(value) => onChange({ config: { ...config, level: value } })}
            />
        </div>
    );
}

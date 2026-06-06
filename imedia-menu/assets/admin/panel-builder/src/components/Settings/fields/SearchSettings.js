import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, ToggleControl } from '@wordpress/components';

const STYLE_OPTIONS = [
    { label: __('Full Form', 'imedia-menu'), value: 'full' },
    { label: __('Compact', 'imedia-menu'), value: 'compact' },
];

export default function SearchSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Placeholder', 'imedia-menu')}
                value={config.placeholder || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, placeholder: value } })
                }
            />
            <SelectControl
                label={__('Style', 'imedia-menu')}
                value={config.style || 'full'}
                options={STYLE_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, style: value } })
                }
            />
            <ToggleControl
                label={__('Icon Only', 'imedia-menu')}
                checked={config.icon_only || false}
                onChange={(value) =>
                    onChange({ config: { ...config, icon_only: value } })
                }
            />
        </div>
    );
}

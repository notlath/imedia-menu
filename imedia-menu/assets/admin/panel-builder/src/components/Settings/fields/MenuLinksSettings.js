import { __ } from '@wordpress/i18n';
import { SelectControl, ToggleControl } from '@wordpress/components';

const SOURCE_OPTIONS = [
    { label: __('Child Menu Items', 'imedia-menu'), value: 'children' },
];

export default function MenuLinksSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Source', 'imedia-menu')}
                value={config.source || 'children'}
                options={SOURCE_OPTIONS}
                onChange={(value) => onChange({ config: { ...config, source: value } })}
            />
            <ToggleControl
                label={__('Show Descriptions', 'imedia-menu')}
                checked={config.show_descriptions || false}
                onChange={(value) =>
                    onChange({ config: { ...config, show_descriptions: value } })
                }
            />
            <ToggleControl
                label={__('Show Icons', 'imedia-menu')}
                checked={config.show_icons !== false}
                onChange={(value) =>
                    onChange({ config: { ...config, show_icons: value } })
                }
            />
        </div>
    );
}

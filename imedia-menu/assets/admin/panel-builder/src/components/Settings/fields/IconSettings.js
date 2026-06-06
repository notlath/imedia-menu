import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, ColorPalette } from '@wordpress/components';

const ALIGN_OPTIONS = [
    { label: __('Left', 'imedia-menu'), value: 'left' },
    { label: __('Center', 'imedia-menu'), value: 'center' },
    { label: __('Right', 'imedia-menu'), value: 'right' },
];

export default function IconSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Icon', 'imedia-menu')}
                help={__('Format: provider:icon (e.g. dashicons:admin-home)', 'imedia-menu')}
                value={config.icon || ''}
                onChange={(value) => onChange({ config: { ...config, icon: value } })}
            />
            <TextControl
                label={__('Size', 'imedia-menu')}
                type="text"
                value={config.size || '24px'}
                onChange={(value) => onChange({ config: { ...config, size: value } })}
            />
            <div className="imm-field-row">
                <label>{__('Color', 'imedia-menu')}</label>
                <ColorPalette
                    colors={[]}
                    value={config.color || ''}
                    onChange={(value) => onChange({ config: { ...config, color: value || '' } })}
                />
            </div>
            <SelectControl
                label={__('Alignment', 'imedia-menu')}
                value={config.align || 'left'}
                options={ALIGN_OPTIONS}
                onChange={(value) => onChange({ config: { ...config, align: value } })}
            />
            <TextControl
                label={__('Link URL', 'imedia-menu')}
                type="url"
                value={config.link || ''}
                onChange={(value) => onChange({ config: { ...config, link: value } })}
            />
        </div>
    );
}

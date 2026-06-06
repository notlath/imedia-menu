import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, ColorPalette } from '@wordpress/components';

const STYLE_OPTIONS = [
    { label: __('Solid', 'imedia-menu'), value: 'solid' },
    { label: __('Dashed', 'imedia-menu'), value: 'dashed' },
    { label: __('Dotted', 'imedia-menu'), value: 'dotted' },
];

export default function DividerSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Height', 'imedia-menu')}
                help={__('e.g. 1px, 2px', 'imedia-menu')}
                value={config.height || '1px'}
                onChange={(value) =>
                    onChange({ config: { ...config, height: value } })
                }
            />
            <SelectControl
                label={__('Border Style', 'imedia-menu')}
                value={config.style || 'solid'}
                options={STYLE_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, style: value } })
                }
            />
            <div className="imm-field-row">
                <label>{__('Color', 'imedia-menu')}</label>
                <ColorPalette
                    colors={[]}
                    value={config.color || ''}
                    onChange={(value) =>
                        onChange({ config: { ...config, color: value || '' } })
                    }
                />
            </div>
            <TextControl
                label={__('Margin', 'imedia-menu')}
                help={__('e.g. 8px 0', 'imedia-menu')}
                value={config.margin || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, margin: value } })
                }
            />
        </div>
    );
}

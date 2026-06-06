import { __ } from '@wordpress/i18n';
import { TextControl, TextareaControl } from '@wordpress/components';

export default function GutenbergBlockSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Block Name', 'imedia-menu')}
                help={__('e.g. core/paragraph, core/heading', 'imedia-menu')}
                value={config.block_name || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, block_name: value } })
                }
            />
            <TextareaControl
                label={__('Block Attributes (JSON)', 'imedia-menu')}
                value={
                    config.block_attrs
                        ? JSON.stringify(config.block_attrs, null, 2)
                        : ''
                }
                onChange={(value) => {
                    try {
                        const parsed = JSON.parse(value);
                        onChange({
                            config: { ...config, block_attrs: parsed },
                        });
                    } catch {
                        // Invalid JSON, ignore
                    }
                }}
            />
        </div>
    );
}

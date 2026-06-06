import { __ } from '@wordpress/i18n';
import { TextareaControl } from '@wordpress/components';

export default function ShortcodeSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextareaControl
                label={__('Shortcode', 'imedia-menu')}
                help={__('e.g. [contact-form-7 id="123"]', 'imedia-menu')}
                value={config.shortcode || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, shortcode: value } })
                }
            />
        </div>
    );
}

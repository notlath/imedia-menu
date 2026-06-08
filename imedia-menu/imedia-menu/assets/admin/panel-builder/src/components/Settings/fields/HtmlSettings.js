import { __ } from '@wordpress/i18n';
import { TextareaControl } from '@wordpress/components';

export default function HtmlSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextareaControl
                label={__('Custom HTML', 'imedia-menu')}
                help={__('Arbitrary HTML markup. Use with caution.', 'imedia-menu')}
                value={config.html || ''}
                onChange={(value) => onChange({ config: { ...config, html: value } })}
            />
        </div>
    );
}

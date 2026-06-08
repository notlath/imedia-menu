import { __ } from '@wordpress/i18n';
import { TextareaControl } from '@wordpress/components';

export default function TextSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextareaControl
                label={__('Content', 'imedia-menu')}
                help={__('HTML content is allowed.', 'imedia-menu')}
                value={config.content || ''}
                onChange={(value) => onChange({ config: { ...config, content: value } })}
            />
        </div>
    );
}

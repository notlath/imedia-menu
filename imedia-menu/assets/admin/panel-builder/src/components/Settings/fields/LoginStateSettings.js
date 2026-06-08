import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';

const FALLBACK_OPTIONS = [
    { label: __('Render empty wrapper', 'imedia-menu'), value: 'empty' },
    { label: __('Hide entirely', 'imedia-menu'), value: 'hide' },
];

export default function LoginStateSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Fallback when branch is empty', 'imedia-menu')}
                value={config.fallback || 'empty'}
                options={FALLBACK_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, fallback: value } })
                }
            />
            <p className="imm-help">
                {__('Edit the Logged In and Logged Out branches in the canvas tree on the left. Each branch is a list of content blocks rendered only for that audience.', 'imedia-menu')}
            </p>
        </div>
    );
}

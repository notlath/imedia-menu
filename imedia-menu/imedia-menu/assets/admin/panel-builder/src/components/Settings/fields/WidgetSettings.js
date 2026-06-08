import { __ } from '@wordpress/i18n';
import { TextControl } from '@wordpress/components';

export default function WidgetSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Widget Area ID', 'imedia-menu')}
                help={__('Enter the sidebar/widget area ID.', 'imedia-menu')}
                value={config.widget_area || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, widget_area: value } })
                }
            />
        </div>
    );
}

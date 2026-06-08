import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl } from '@wordpress/components';

export default function RealWidgetSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <TextControl
                label={__('Widget Class', 'imedia-menu')}
                help={__('PHP class name of a registered WP_Widget (e.g. WP_Widget_Recent_Posts).', 'imedia-menu')}
                value={config.widget_class || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, widget_class: value } })
                }
            />
            <TextControl
                label={__('Title', 'imedia-menu')}
                help={__('Optional widget title.', 'imedia-menu')}
                value={config.title || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, title: value } })
                }
            />
            <TextControl
                label={__('Before Widget', 'imedia-menu')}
                help={__('HTML wrapper before the widget. Use %1$s for id, %2$s for class.', 'imedia-menu')}
                value={config.before_widget || '<div id="%1$s" class="widget %2$s">'}
                onChange={(value) =>
                    onChange({ config: { ...config, before_widget: value } })
                }
            />
            <TextControl
                label={__('After Widget', 'imedia-menu')}
                value={config.after_widget || '</div>'}
                onChange={(value) =>
                    onChange({ config: { ...config, after_widget: value } })
                }
            />
            <TextControl
                label={__('Before Title', 'imedia-menu')}
                value={config.before_title || '<h2 class="widgettitle">'}
                onChange={(value) =>
                    onChange({ config: { ...config, before_title: value } })
                }
            />
            <TextControl
                label={__('After Title', 'imedia-menu')}
                value={config.after_title || '</h2>'}
                onChange={(value) =>
                    onChange({ config: { ...config, after_title: value } })
                }
            />
        </div>
    );
}

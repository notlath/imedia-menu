import { __ } from '@wordpress/i18n';
import { SelectControl, TextControl, ToggleControl } from '@wordpress/components';

const POST_TYPE_OPTIONS = [
    { label: __('Posts', 'imedia-menu'), value: 'post' },
    { label: __('Pages', 'imedia-menu'), value: 'page' },
];

const ORDERBY_OPTIONS = [
    { label: __('Date', 'imedia-menu'), value: 'date' },
    { label: __('Title', 'imedia-menu'), value: 'title' },
    { label: __('Menu Order', 'imedia-menu'), value: 'menu_order' },
    { label: __('Random', 'imedia-menu'), value: 'rand' },
];

const ORDER_OPTIONS = [
    { label: __('DESC', 'imedia-menu'), value: 'DESC' },
    { label: __('ASC', 'imedia-menu'), value: 'ASC' },
];

export default function PostListingSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Post Type', 'imedia-menu')}
                value={config.post_type || 'post'}
                options={POST_TYPE_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, post_type: value } })
                }
            />
            <TextControl
                label={__('Count', 'imedia-menu')}
                type="number"
                min={1}
                max={20}
                value={config.count ?? 5}
                onChange={(value) =>
                    onChange({ config: { ...config, count: parseInt(value, 10) || 5 } })
                }
            />
            <SelectControl
                label={__('Order By', 'imedia-menu')}
                value={config.orderby || 'date'}
                options={ORDERBY_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, orderby: value } })
                }
            />
            <SelectControl
                label={__('Order', 'imedia-menu')}
                value={config.order || 'DESC'}
                options={ORDER_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, order: value } })
                }
            />
            <ToggleControl
                label={__('Show Thumbnail', 'imedia-menu')}
                checked={config.show_thumbnail || false}
                onChange={(value) =>
                    onChange({ config: { ...config, show_thumbnail: value } })
                }
            />
            <ToggleControl
                label={__('Show Excerpt', 'imedia-menu')}
                checked={config.show_excerpt || false}
                onChange={(value) =>
                    onChange({ config: { ...config, show_excerpt: value } })
                }
            />
            <ToggleControl
                label={__('Ajax Loading', 'imedia-menu')}
                checked={config.ajax_loading || false}
                onChange={(value) =>
                    onChange({ config: { ...config, ajax_loading: value } })
                }
            />
        </div>
    );
}

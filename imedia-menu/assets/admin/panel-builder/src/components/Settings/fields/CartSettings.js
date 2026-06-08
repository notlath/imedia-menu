import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, ToggleControl } from '@wordpress/components';

const DISPLAY_OPTIONS = [
    { label: __('Icon only', 'imedia-menu'), value: 'icon' },
    { label: __('Mini (icon + up to 3 items)', 'imedia-menu'), value: 'mini' },
    { label: __('Full (icon + all items)', 'imedia-menu'), value: 'full' },
];

export default function CartSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Display mode', 'imedia-menu')}
                value={config.display || 'icon'}
                options={DISPLAY_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, display: value } })
                }
            />
            <TextControl
                label={__('Cart URL', 'imedia-menu')}
                help={__('Leave blank to use the WooCommerce default cart URL.', 'imedia-menu')}
                type="url"
                value={config.cart_url || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, cart_url: value } })
                }
            />
            <TextControl
                label={__('Empty text', 'imedia-menu')}
                help={__('Shown when the cart has no items.', 'imedia-menu')}
                value={config.empty_text || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, empty_text: value } })
                }
            />
            <TextControl
                label={__('Icon', 'imedia-menu')}
                help={__('Dashicons class name (e.g. dashicons-cart).', 'imedia-menu')}
                value={config.icon || 'dashicons-cart'}
                onChange={(value) =>
                    onChange({ config: { ...config, icon: value } })
                }
            />
            <ToggleControl
                label={__('Show item count', 'imedia-menu')}
                checked={!!config.show_count}
                onChange={(value) =>
                    onChange({ config: { ...config, show_count: value } })
                }
            />
            <ToggleControl
                label={__('Show subtotal', 'imedia-menu')}
                checked={!!config.show_total}
                onChange={(value) =>
                    onChange({ config: { ...config, show_total: value } })
                }
            />
            <ToggleControl
                label={__('Show item thumbnails', 'imedia-menu')}
                help={__('Only applies to mini and full display modes.', 'imedia-menu')}
                checked={!!config.show_thumbnails}
                onChange={(value) =>
                    onChange({ config: { ...config, show_thumbnails: value } })
                }
            />
            <ToggleControl
                label={__('Hide when cart is empty', 'imedia-menu')}
                checked={!!config.hide_when_empty}
                onChange={(value) =>
                    onChange({ config: { ...config, hide_when_empty: value } })
                }
            />
        </div>
    );
}

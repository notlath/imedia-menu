import { __ } from '@wordpress/i18n';
import { SelectControl, ToggleControl } from '@wordpress/components';

const TAXONOMY_OPTIONS = [
    { label: __('Categories', 'imedia-menu'), value: 'category' },
    { label: __('Tags', 'imedia-menu'), value: 'post_tag' },
];

const ORDERBY_OPTIONS = [
    { label: __('Name', 'imedia-menu'), value: 'name' },
    { label: __('Count', 'imedia-menu'), value: 'count' },
    { label: __('Slug', 'imedia-menu'), value: 'slug' },
];

const ORDER_OPTIONS = [
    { label: __('ASC', 'imedia-menu'), value: 'ASC' },
    { label: __('DESC', 'imedia-menu'), value: 'DESC' },
];

export default function TaxonomyListingSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Taxonomy', 'imedia-menu')}
                value={config.taxonomy || 'category'}
                options={TAXONOMY_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, taxonomy: value } })
                }
            />
            <ToggleControl
                label={__('Hide Empty', 'imedia-menu')}
                checked={config.hide_empty !== false}
                onChange={(value) =>
                    onChange({ config: { ...config, hide_empty: value } })
                }
            />
            <ToggleControl
                label={__('Show Count', 'imedia-menu')}
                checked={config.show_count || false}
                onChange={(value) =>
                    onChange({ config: { ...config, show_count: value } })
                }
            />
            <SelectControl
                label={__('Order By', 'imedia-menu')}
                value={config.orderby || 'name'}
                options={ORDERBY_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, orderby: value } })
                }
            />
            <SelectControl
                label={__('Order', 'imedia-menu')}
                value={config.order || 'ASC'}
                options={ORDER_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, order: value } })
                }
            />
        </div>
    );
}

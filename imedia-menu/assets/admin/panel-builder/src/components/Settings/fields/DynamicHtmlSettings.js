import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, TextareaControl } from '@wordpress/components';

const SOURCE_OPTIONS = [
    { label: __('Fetch from URL', 'imedia-menu'), value: 'url' },
    { label: __('PHP callback', 'imedia-menu'), value: 'callback' },
];

const METHOD_OPTIONS = [
    { label: 'GET', value: 'GET' },
    { label: 'POST', value: 'POST' },
];

export default function DynamicHtmlSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Source', 'imedia-menu')}
                value={config.source || 'url'}
                options={SOURCE_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, source: value } })
                }
            />
            {config.source === 'callback' ? (
                <TextControl
                    label={__('Callable', 'imedia-menu')}
                    help={__('PHP callable string (e.g. my_function) or [Class, method].', 'imedia-menu')}
                    value={config.callback || ''}
                    onChange={(value) =>
                        onChange({ config: { ...config, callback: value } })
                    }
                />
            ) : (
                <>
                    <TextControl
                        label={__('URL', 'imedia-menu')}
                        type="url"
                        value={config.url || ''}
                        onChange={(value) =>
                            onChange({ config: { ...config, url: value } })
                        }
                    />
                    <SelectControl
                        label={__('Method', 'imedia-menu')}
                        value={config.method || 'GET'}
                        options={METHOD_OPTIONS}
                        onChange={(value) =>
                            onChange({ config: { ...config, method: value } })
                        }
                    />
                </>
            )}
            <TextControl
                label={__('Cache TTL (seconds)', 'imedia-menu')}
                type="number"
                value={String(config.cache_ttl ?? 300)}
                onChange={(value) =>
                    onChange({ config: { ...config, cache_ttl: parseInt(value, 10) || 0 } })
                }
            />
            <TextControl
                label={__('Timeout (seconds)', 'imedia-menu')}
                type="number"
                value={String(config.timeout ?? 5)}
                onChange={(value) =>
                    onChange({ config: { ...config, timeout: parseInt(value, 10) || 5 } })
                }
            />
        </div>
    );
}

import { __ } from '@wordpress/i18n';
import { TextControl, SelectControl, Button, IconButton } from '@wordpress/components';
import { useState } from '@wordpress/element';

const ORIENTATION_OPTIONS = [
    { label: __('Horizontal', 'imedia-menu'), value: 'horizontal' },
    { label: __('Vertical', 'imedia-menu'), value: 'vertical' },
];

export default function TabbedSettings({ config, onChange }) {
    const tabs = Array.isArray(config.tabs) ? config.tabs : [];

    const addTab = () => {
        const newTab = { id: 'tab-' + Date.now(), label: 'New Tab', blocks: [] };
        onChange({ config: { ...config, tabs: [...tabs, newTab] } });
    };

    const updateTab = (index, updates) => {
        const next = tabs.map((t, i) => (i === index ? { ...t, ...updates } : t));
        onChange({ config: { ...config, tabs: next } });
    };

    const removeTab = (index) => {
        onChange({ config: { ...config, tabs: tabs.filter((_, i) => i !== index) } });
    };

    return (
        <div className="imm-field-group">
            <SelectControl
                label={__('Orientation', 'imedia-menu')}
                value={config.orientation || 'horizontal'}
                options={ORIENTATION_OPTIONS}
                onChange={(value) =>
                    onChange({ config: { ...config, orientation: value } })
                }
            />
            <TextControl
                label={__('Default Tab ID', 'imedia-menu')}
                help={__('Leave blank to use the first tab.', 'imedia-menu')}
                value={config.default_tab || ''}
                onChange={(value) =>
                    onChange({ config: { ...config, default_tab: value } })
                }
            />
            <div className="imm-repeat-group">
                <h4>{__('Tabs', 'imedia-menu')}</h4>
                {tabs.map((tab, i) => (
                    <div key={i} className="imm-repeat-item">
                        <TextControl
                            label={__('Tab ID', 'imedia-menu')}
                            value={tab.id || ''}
                            onChange={(value) => updateTab(i, { id: value })}
                        />
                        <TextControl
                            label={__('Label', 'imedia-menu')}
                            value={tab.label || ''}
                            onChange={(value) => updateTab(i, { label: value })}
                        />
                        <Button
                            variant="link"
                            isDestructive
                            onClick={() => removeTab(i)}
                        >
                            {__('Remove Tab', 'imedia-menu')}
                        </Button>
                    </div>
                ))}
                <Button variant="secondary" onClick={addTab}>
                    {__('Add Tab', 'imedia-menu')}
                </Button>
            </div>
        </div>
    );
}

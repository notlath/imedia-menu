import { __ } from '@wordpress/i18n';
import { TextControl, ToggleControl, Button } from '@wordpress/components';

export default function AccordionSettings({ config, onChange }) {
    const items = Array.isArray(config.items) ? config.items : [];

    const addItem = () => {
        const newItem = {
            id: 'acc-' + Date.now(),
            label: 'New Item',
            blocks: [],
            initially_open: false,
        };
        onChange({ config: { ...config, items: [...items, newItem] } });
    };

    const updateItem = (index, updates) => {
        const next = items.map((it, i) => (i === index ? { ...it, ...updates } : it));
        onChange({ config: { ...config, items: next } });
    };

    const removeItem = (index) => {
        onChange({ config: { ...config, items: items.filter((_, i) => i !== index) } });
    };

    return (
        <div className="imm-field-group">
            <ToggleControl
                label={__('Allow multiple open', 'imedia-menu')}
                help={__('If on, more than one item can be open at a time.', 'imedia-menu')}
                checked={!!config.multi_open}
                onChange={(value) =>
                    onChange({ config: { ...config, multi_open: value } })
                }
            />
            <div className="imm-repeat-group">
                <h4>{__('Items', 'imedia-menu')}</h4>
                {items.map((item, i) => (
                    <div key={i} className="imm-repeat-item">
                        <TextControl
                            label={__('Item ID', 'imedia-menu')}
                            value={item.id || ''}
                            onChange={(value) => updateItem(i, { id: value })}
                        />
                        <TextControl
                            label={__('Label', 'imedia-menu')}
                            value={item.label || ''}
                            onChange={(value) => updateItem(i, { label: value })}
                        />
                        <ToggleControl
                            label={__('Initially open', 'imedia-menu')}
                            checked={!!item.initially_open}
                            onChange={(value) => updateItem(i, { initially_open: value })}
                        />
                        <Button
                            variant="link"
                            isDestructive
                            onClick={() => removeItem(i)}
                        >
                            {__('Remove Item', 'imedia-menu')}
                        </Button>
                    </div>
                ))}
                <Button variant="secondary" onClick={addItem}>
                    {__('Add Item', 'imedia-menu')}
                </Button>
            </div>
        </div>
    );
}

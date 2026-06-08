import { __ } from '@wordpress/i18n';
import { TextareaControl, ToggleControl, Button } from '@wordpress/components';
import { useState } from '@wordpress/element';

const TOKEN_OPTIONS = [
    { token: '{user_name}', label: __('Current user name', 'imedia-menu') },
    { token: '{user_email}', label: __('Current user email', 'imedia-menu') },
    { token: '{user_id}', label: __('Current user ID', 'imedia-menu') },
    { token: '{site_title}', label: __('Site title', 'imedia-menu') },
    { token: '{site_url}', label: __('Site URL', 'imedia-menu') },
    { token: '{date}', label: __('Current date', 'imedia-menu') },
    { token: '{time}', label: __('Current time', 'imedia-menu') },
    { token: '{cart_count}', label: __('WooCommerce cart count', 'imedia-menu') },
    { token: '{cart_total}', label: __('WooCommerce cart subtotal', 'imedia-menu') },
    { token: '{ip}', label: __('Visitor IP', 'imedia-menu') },
];

export default function ReplacementsSettings({ config, onChange }) {
    const [text, setText] = useState(config.template || '');

    const insertToken = (token) => {
        const newText = (text || '') + token;
        setText(newText);
        onChange({ config: { ...config, template: newText } });
    };

    return (
        <div className="imm-field-group">
            <TextareaControl
                label={__('Template', 'imedia-menu')}
                help={__('Click a token below to append it. Unknown tokens are left as literal text.', 'imedia-menu')}
                value={text}
                onChange={(value) => {
                    setText(value);
                    onChange({ config: { ...config, template: value } });
                }}
                rows={5}
            />
            <div className="imm-token-grid">
                {TOKEN_OPTIONS.map((t) => (
                    <Button
                        key={t.token}
                        variant="secondary"
                        size="small"
                        onClick={() => insertToken(t.token)}
                    >
                        {t.label}
                    </Button>
                ))}
            </div>
            <ToggleControl
                label={__('Parse shortcodes', 'imedia-menu')}
                help={__('Run do_shortcode() on the substituted output.', 'imedia-menu')}
                checked={!!config.parse_shortcodes}
                onChange={(value) =>
                    onChange({ config: { ...config, parse_shortcodes: value } })
                }
            />
        </div>
    );
}

import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Modal, TextControl, TextareaControl, Button, Notice } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { createTemplate } from '../../api/templates';
import { STORE_NAME } from '../../data/constants';

export default function TemplateSaveModal({ onClose }) {
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const { config, styles } = useSelect(
        (select) => ({
            config: select(STORE_NAME).getConfig(),
            styles: select(STORE_NAME).getStyles(),
        }),
        []
    );

    const handleSave = async () => {
        const trimmed = name.trim();
        if (!trimmed) return;

        setSaving(true);
        setError(null);

        try {
            const meta = {
                panel_width: config.panel_width || 'container',
                animation_type: config.animation_type || 'fade',
                column_count: config.column_count || 3,
            };
            await createTemplate(trimmed, description, config, styles, meta);
            setSuccess(true);
        } catch {
            setError(__('Failed to save template.', 'imedia-menu'));
        } finally {
            setSaving(false);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        handleSave();
    };

    return (
        <Modal
            title={__('Save as Template', 'imedia-menu')}
            onRequestClose={onClose}
            size="small"
        >
            <form onSubmit={handleSubmit}>
                {success && (
                    <Notice status="success" isDismissible={false}>
                        {__('Template saved successfully!', 'imedia-menu')}
                    </Notice>
                )}

                {error && (
                    <Notice status="error" isDismissible={false}>
                        {error}
                    </Notice>
                )}

                <TextControl
                    label={__('Template Name', 'imedia-menu')}
                    value={name}
                    onChange={setName}
                    required
                    disabled={saving || success}
                    autoFocus
                />

                <TextareaControl
                    label={__('Description (optional)', 'imedia-menu')}
                    value={description}
                    onChange={setDescription}
                    disabled={saving || success}
                />

                <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end', marginTop: '16px' }}>
                    {!success && (
                        <>
                            <Button
                                variant="secondary"
                                onClick={onClose}
                                disabled={saving}
                            >
                                {__('Cancel', 'imedia-menu')}
                            </Button>
                            <Button
                                variant="primary"
                                onClick={handleSave}
                                disabled={saving || !name.trim()}
                                isBusy={saving}
                            >
                                {saving
                                    ? __('Saving...', 'imedia-menu')
                                    : __('Save Template', 'imedia-menu')}
                            </Button>
                        </>
                    )}
                    {success && (
                        <Button variant="primary" onClick={onClose}>
                            {__('Close', 'imedia-menu')}
                        </Button>
                    )}
                </div>
            </form>
        </Modal>
    );
}

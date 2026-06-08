import { useState, useEffect } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import {
    Modal,
    Button,
    Spinner,
    Notice,
    TextControl,
    Flex,
    FlexItem,
} from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { fetchTemplates, updateTemplate, deleteTemplate } from '../../api/templates';
import { STORE_NAME } from '../../data/constants';

function formatDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function ConfirmDeleteModal({ template, onConfirm, onCancel, deleting }) {
    return (
        <Modal
            title={__('Delete Template', 'imedia-menu')}
            onRequestClose={onCancel}
            size="small"
        >
            <p>
                {sprintf(
                    __('Are you sure you want to delete "%s"?', 'imedia-menu'),
                    template.name
                )}
            </p>
            <p>{__('This action cannot be undone.', 'imedia-menu')}</p>
            <Flex justify="flex-end" gap={3}>
                <FlexItem>
                    <Button variant="secondary" onClick={onCancel} disabled={deleting}>
                        {__('Cancel', 'imedia-menu')}
                    </Button>
                </FlexItem>
                <FlexItem>
                    <Button
                        variant="destructive"
                        onClick={onConfirm}
                        disabled={deleting}
                        isBusy={deleting}
                    >
                        {deleting
                            ? __('Deleting...', 'imedia-menu')
                            : __('Delete', 'imedia-menu')}
                    </Button>
                </FlexItem>
            </Flex>
        </Modal>
    );
}

export default function TemplateBrowserModal({ onClose }) {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [renaming, setRenaming] = useState(null);
    const [renameValue, setRenameValue] = useState('');
    const [savingRename, setSavingRename] = useState(false);
    const [deleteTarget, setDeleteTarget] = useState(null);
    const [deleting, setDeleting] = useState(false);

    const templates = useSelect((select) => select(STORE_NAME).getTemplates(), []);
    const dispatch = useDispatch(STORE_NAME);

    const { config, styles } = useSelect(
        (select) => ({
            config: select(STORE_NAME).getConfig(),
            styles: select(STORE_NAME).getStyles(),
        }),
        []
    );

    const loadTemplates = async () => {
        setLoading(true);
        setError(null);
        try {
            const data = await fetchTemplates();
            dispatch.setTemplates(data);
        } catch {
            setError(__('Failed to load templates.', 'imedia-menu'));
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadTemplates();
    }, []);

    const handleApply = (tmpl) => {
        dispatch.applyTemplate(tmpl.config, tmpl.styles);
        onClose();
    };

    const handleStartRename = (tmpl) => {
        setRenaming(tmpl.id);
        setRenameValue(tmpl.name);
    };

    const handleSaveRename = async () => {
        if (!renameValue.trim() || !renaming) return;
        setSavingRename(true);
        try {
            await updateTemplate(renaming, { name: renameValue.trim() });
            const updated = templates.map((t) =>
                t.id === renaming ? { ...t, name: renameValue.trim() } : t
            );
            dispatch.setTemplates(updated);
            setRenaming(null);
        } catch {
            setError(__('Failed to rename template.', 'imedia-menu'));
        } finally {
            setSavingRename(false);
        }
    };

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setDeleting(true);
        try {
            await deleteTemplate(deleteTarget.id);
            const updated = templates.filter((t) => t.id !== deleteTarget.id);
            dispatch.setTemplates(updated);
            setDeleteTarget(null);
        } catch {
            setError(__('Failed to delete template.', 'imedia-menu'));
        } finally {
            setDeleting(false);
        }
    };

    return (
        <Modal
            title={__('Load Template', 'imedia-menu')}
            onRequestClose={onClose}
            size="large"
        >
            {error && (
                <Notice status="error" isDismissible onRemove={() => setError(null)}>
                    {error}
                </Notice>
            )}

            {loading && (
                <div style={{ textAlign: 'center', padding: '40px 0' }}>
                    <Spinner />
                    <p>{__('Loading templates...', 'imedia-menu')}</p>
                </div>
            )}

            {!loading && templates.length === 0 && (
                <div style={{ textAlign: 'center', padding: '40px 0', color: '#757575' }}>
                    <p style={{ fontSize: '16px', marginBottom: '8px' }}>
                        {__('No templates saved yet.', 'imedia-menu')}
                    </p>
                    <p>
                        {__(
                            'Build a panel layout, then use "Save as Template" to create your first template.',
                            'imedia-menu'
                        )}
                    </p>
                </div>
            )}

            {!loading && templates.length > 0 && (
                <div
                    style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
                        gap: '16px',
                        maxHeight: '60vh',
                        overflowY: 'auto',
                        padding: '4px 0',
                    }}
                >
                    {templates.map((tmpl) => (
                        <div
                            key={tmpl.id}
                            style={{
                                border: '1px solid #ddd',
                                borderRadius: '4px',
                                padding: '16px',
                                display: 'flex',
                                flexDirection: 'column',
                                gap: '8px',
                                background: '#fff',
                            }}
                        >
                            {renaming === tmpl.id ? (
                                <div style={{ display: 'flex', gap: '4px', alignItems: 'center' }}>
                                    <TextControl
                                        value={renameValue}
                                        onChange={setRenameValue}
                                        disabled={savingRename}
                                        autoFocus
                                        style={{ marginBottom: 0, flex: 1 }}
                                        onKeyDown={(e) => {
                                            if (e.key === 'Enter') handleSaveRename();
                                            if (e.key === 'Escape') setRenaming(null);
                                        }}
                                    />
                                    <Button
                                        icon="yes"
                                        label={__('Save', 'imedia-menu')}
                                        size="small"
                                        onClick={handleSaveRename}
                                        disabled={savingRename || !renameValue.trim()}
                                    />
                                    <Button
                                        icon="no"
                                        label={__('Cancel', 'imedia-menu')}
                                        size="small"
                                        onClick={() => setRenaming(null)}
                                        disabled={savingRename}
                                    />
                                </div>
                            ) : (
                                <div
                                    style={{
                                        fontWeight: 600,
                                        fontSize: '14px',
                                        display: 'flex',
                                        justifyContent: 'space-between',
                                        alignItems: 'center',
                                    }}
                                >
                                    <span style={{ wordBreak: 'break-word' }}>
                                        {tmpl.name}
                                    </span>
                                    <Button
                                        icon="edit"
                                        label={__('Rename', 'imedia-menu')}
                                        size="small"
                                        onClick={() => handleStartRename(tmpl)}
                                    />
                                </div>
                            )}

                            {tmpl.description && (
                                <p
                                    style={{
                                        margin: 0,
                                        fontSize: '12px',
                                        color: '#757575',
                                        lineHeight: '1.4',
                                        display: '-webkit-box',
                                        WebkitLineClamp: 2,
                                        WebkitBoxOrient: 'vertical',
                                        overflow: 'hidden',
                                    }}
                                >
                                    {tmpl.description}
                                </p>
                            )}

                            <div style={{ display: 'flex', gap: '8px', fontSize: '11px', color: '#757575' }}>
                                {tmpl.meta?.panel_width && (
                                    <span style={{ background: '#f0f0f1', padding: '2px 6px', borderRadius: '3px' }}>
                                        {tmpl.meta.panel_width}
                                    </span>
                                )}
                                {tmpl.meta?.column_count && (
                                    <span style={{ background: '#f0f0f1', padding: '2px 6px', borderRadius: '3px' }}>
                                        {tmpl.meta.column_count} col
                                    </span>
                                )}
                                {tmpl.meta?.animation_type && (
                                    <span style={{ background: '#f0f0f1', padding: '2px 6px', borderRadius: '3px' }}>
                                        {tmpl.meta.animation_type}
                                    </span>
                                )}
                            </div>

                            <div style={{ fontSize: '11px', color: '#999' }}>
                                {tmpl.created_at && formatDate(tmpl.created_at)}
                            </div>

                            <div
                                style={{
                                    display: 'flex',
                                    gap: '6px',
                                    marginTop: 'auto',
                                    paddingTop: '8px',
                                    borderTop: '1px solid #f0f0f1',
                                }}
                            >
                                <Button
                                    variant="primary"
                                    size="small"
                                    onClick={() => handleApply(tmpl)}
                                >
                                    {__('Apply', 'imedia-menu')}
                                </Button>
                                <Button
                                    variant="secondary"
                                    size="small"
                                    icon="trash"
                                    label={__('Delete', 'imedia-menu')}
                                    onClick={() => setDeleteTarget(tmpl)}
                                />
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {deleteTarget && (
                <ConfirmDeleteModal
                    template={deleteTarget}
                    onConfirm={handleDelete}
                    onCancel={() => setDeleteTarget(null)}
                    deleting={deleting}
                />
            )}
        </Modal>
    );
}

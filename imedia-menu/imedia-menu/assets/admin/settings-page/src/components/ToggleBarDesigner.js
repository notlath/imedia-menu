import { useState, useEffect } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import {
    Button,
    Modal,
    IconButton,
    TextControl,
    SelectControl,
    Spinner,
    Notice,
    Flex,
    FlexItem,
} from '@wordpress/components';
import {
    fetchToggleBar,
    saveToggleBar,
} from '../api/toggle-bar';

const BLOCK_TYPES = [
    { type: 'menu_toggle', label: __('Menu Toggle', 'imedia-menu'), icon: '☰' },
    { type: 'menu_toggle_animated', label: __('Animated Toggle', 'imedia-menu'), icon: '⇅' },
    { type: 'spacer', label: __('Spacer', 'imedia-menu'), icon: '↔' },
    { type: 'search', label: __('Search', 'imedia-menu'), icon: '🔍' },
    { type: 'logo', label: __('Logo', 'imedia-menu'), icon: '🖼' },
    { type: 'icon', label: __('Icon', 'imedia-menu'), icon: '★' },
    { type: 'html', label: __('Custom HTML', 'imedia-menu'), icon: '</>' },
    { type: 'custom', label: __('Shortcode', 'imedia-menu'), icon: '[ ]' },
];

const ALIGNS = [
    { value: 'left', label: __('Left', 'imedia-menu') },
    { value: 'center', label: __('Center', 'imedia-menu') },
    { value: 'right', label: __('Right', 'imedia-menu') },
];

function generateId() {
    return 'block_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7);
}

function defaultSettingsFor(type) {
    switch (type) {
        case 'menu_toggle':
            return { label: __('Menu', 'imedia-menu'), icon_only: false, aria_label: __('Toggle navigation menu', 'imedia-menu') };
        case 'menu_toggle_animated':
            return { animation: 'arrow', label: __('Menu', 'imedia-menu'), icon_only: false, aria_label: __('Toggle navigation menu', 'imedia-menu') };
        case 'spacer':
            return { width: '20px' };
        case 'search':
            return { placeholder: __('Search...', 'imedia-menu') };
        case 'logo':
            return { url: '/', target: '_self', alt: '' };
        case 'icon':
            return { url: '', target: '_self', aria_label: '' };
        case 'html':
            return { content: '' };
        case 'custom':
            return { shortcode: '' };
        default:
            return {};
    }
}

function BlockSettingsPanel({ block, onChange, onClose }) {
    if (!block) return null;

    const updateSetting = (key, value) => {
        onChange({ ...block, settings: { ...block.settings, [key]: value } });
    };

    return (
        <div className="imm-toggle-bar-block-settings">
            <Flex justify="space-between" align="center">
                <FlexItem>
                    <strong>{sprintf(__('Settings: %s', 'imedia-menu'), block.type)}</strong>
                </FlexItem>
                <FlexItem>
                    <Button variant="tertiary" onClick={onClose}>
                        {__('Done', 'imedia-menu')}
                    </Button>
                </FlexItem>
            </Flex>

            <div style={{ marginTop: '12px' }}>
                {block.type === 'menu_toggle' && (
                    <>
                        <TextControl
                            label={__('Label', 'imedia-menu')}
                            value={block.settings.label || ''}
                            onChange={(v) => updateSetting('label', v)}
                        />
                        <TextControl
                            label={__('ARIA Label', 'imedia-menu')}
                            value={block.settings.aria_label || ''}
                            onChange={(v) => updateSetting('aria_label', v)}
                        />
                    </>
                )}

                {block.type === 'menu_toggle_animated' && (
                    <>
                        <SelectControl
                            label={__('Animation', 'imedia-menu')}
                            value={block.settings.animation || 'arrow'}
                            options={[
                                { value: 'arrow', label: __('Arrow', 'imedia-menu') },
                                { value: 'slider', label: __('Slider', 'imedia-menu') },
                            ]}
                            onChange={(v) => updateSetting('animation', v)}
                        />
                        <TextControl
                            label={__('Label', 'imedia-menu')}
                            value={block.settings.label || ''}
                            onChange={(v) => updateSetting('label', v)}
                        />
                    </>
                )}

                {block.type === 'spacer' && (
                    <TextControl
                        label={__('Width (CSS)', 'imedia-menu')}
                        value={block.settings.width || '20px'}
                        onChange={(v) => updateSetting('width', v)}
                    />
                )}

                {block.type === 'search' && (
                    <TextControl
                        label={__('Placeholder', 'imedia-menu')}
                        value={block.settings.placeholder || ''}
                        onChange={(v) => updateSetting('placeholder', v)}
                    />
                )}

                {block.type === 'logo' && (
                    <>
                        <TextControl
                            label={__('Logo Attachment ID', 'imedia-menu')}
                            type="number"
                            value={block.settings.logo_id || 0}
                            onChange={(v) => updateSetting('logo_id', parseInt(v, 10) || 0)}
                        />
                        <TextControl
                            label={__('URL', 'imedia-menu')}
                            value={block.settings.url || ''}
                            onChange={(v) => updateSetting('url', v)}
                        />
                        <SelectControl
                            label={__('Target', 'imedia-menu')}
                            value={block.settings.target || '_self'}
                            options={[
                                { value: '_self', label: __('Same Window', 'imedia-menu') },
                                { value: '_blank', label: __('New Tab', 'imedia-menu') },
                            ]}
                            onChange={(v) => updateSetting('target', v)}
                        />
                        <TextControl
                            label={__('Alt Text', 'imedia-menu')}
                            value={block.settings.alt || ''}
                            onChange={(v) => updateSetting('alt', v)}
                        />
                    </>
                )}

                {block.type === 'icon' && (
                    <>
                        <TextControl
                            label={__('Icon (dashicon name or HTML)', 'imedia-menu')}
                            value={block.settings.icon || ''}
                            onChange={(v) => updateSetting('icon', v)}
                        />
                        <TextControl
                            label={__('URL (optional)', 'imedia-menu')}
                            value={block.settings.url || ''}
                            onChange={(v) => updateSetting('url', v)}
                        />
                        <TextControl
                            label={__('ARIA Label', 'imedia-menu')}
                            value={block.settings.aria_label || ''}
                            onChange={(v) => updateSetting('aria_label', v)}
                        />
                    </>
                )}

                {block.type === 'html' && (
                    <TextControl
                        label={__('HTML Content', 'imedia-menu')}
                        value={block.settings.content || ''}
                        onChange={(v) => updateSetting('content', v)}
                        help={__('Allowed tags only. Script tags will be stripped.', 'imedia-menu')}
                    />
                )}

                {block.type === 'custom' && (
                    <TextControl
                        label={__('Shortcode', 'imedia-menu')}
                        value={block.settings.shortcode || ''}
                        onChange={(v) => updateSetting('shortcode', v)}
                    />
                )}
            </div>
        </div>
    );
}

function DraggableBlock({ block, index, onEdit, onDelete, onMoveLeft, onMoveRight, canMoveLeft, canMoveRight }) {
    return (
        <div className="imm-toggle-bar-block" data-block-id={block.id} data-block-type={block.type}>
            <div className="imm-toggle-bar-block-label">
                <span className="imm-toggle-bar-block-icon">
                    {BLOCK_TYPES.find((b) => b.type === block.type)?.icon || '?'}
                </span>
                <span>{BLOCK_TYPES.find((b) => b.type === block.type)?.label || block.type}</span>
            </div>
            <div className="imm-toggle-bar-block-actions">
                <IconButton icon="arrow-left-alt2" label={__('Move left', 'imedia-menu')} onClick={onMoveLeft} disabled={!canMoveLeft} size="small" />
                <IconButton icon="arrow-right-alt2" label={__('Move right', 'imedia-menu')} onClick={onMoveRight} disabled={!canMoveRight} size="small" />
                <IconButton icon="admin-generic" label={__('Settings', 'imedia-menu')} onClick={onEdit} size="small" />
                <IconButton icon="trash" label={__('Delete', 'imedia-menu')} onClick={onDelete} size="small" />
            </div>
        </div>
    );
}

export default function ToggleBarDesigner({ slug, isOpen, onClose }) {
    const [blocks, setBlocks] = useState([]);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [editingBlock, setEditingBlock] = useState(null);
    const [message, setMessage] = useState(null);
    const [draggedIndex, setDraggedIndex] = useState(null);

    useEffect(() => {
        if (!isOpen || !slug) return;
        let cancelled = false;
        const load = async () => {
            setLoading(true);
            setMessage(null);
            try {
                const data = await fetchToggleBar(slug);
                if (cancelled) return;
                setBlocks(data.blocks || []);
            } catch {
                if (!cancelled) {
                    setMessage({ type: 'error', text: __('Failed to load toggle bar.', 'imedia-menu') });
                }
            } finally {
                if (!cancelled) setLoading(false);
            }
        };
        load();
        return () => { cancelled = true; };
    }, [slug, isOpen]);

    const addBlock = (type) => {
        const newBlock = {
            id: generateId(),
            type,
            align: 'left',
            settings: defaultSettingsFor(type),
        };
        setBlocks((prev) => [...prev, newBlock]);
    };

    const updateBlock = (id, updates) => {
        setBlocks((prev) => prev.map((b) => (b.id === id ? { ...b, ...updates } : b)));
    };

    const deleteBlock = (id) => {
        setBlocks((prev) => prev.filter((b) => b.id !== id));
    };

    const moveBlock = (id, direction) => {
        setBlocks((prev) => {
            const idx = prev.findIndex((b) => b.id === id);
            if (idx < 0) return prev;
            const newIdx = direction === 'left' ? idx - 1 : idx + 1;
            if (newIdx < 0 || newIdx >= prev.length) return prev;
            const next = [...prev];
            [next[idx], next[newIdx]] = [next[newIdx], next[idx]];
            return next;
        });
    };

    const changeAlign = (id, align) => {
        updateBlock(id, { align });
    };

    const handleSave = async () => {
        setSaving(true);
        setMessage(null);
        try {
            await saveToggleBar(slug, blocks);
            setMessage({ type: 'success', text: __('Toggle bar saved.', 'imedia-menu') });
        } catch {
            setMessage({ type: 'error', text: __('Failed to save toggle bar.', 'imedia-menu') });
        } finally {
            setSaving(false);
        }
    };

    const handleDragStart = (e, index) => {
        setDraggedIndex(index);
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', String(index));
    };

    const handleDragOver = (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    };

    const handleDrop = (e, targetIndex) => {
        e.preventDefault();
        if (draggedIndex === null || draggedIndex === targetIndex) {
            setDraggedIndex(null);
            return;
        }
        setBlocks((prev) => {
            const next = [...prev];
            const [moved] = next.splice(draggedIndex, 1);
            next.splice(targetIndex, 0, moved);
            return next;
        });
        setDraggedIndex(null);
    };

    const blocksByAlign = (align) => blocks.filter((b) => b.align === align);

    if (!isOpen) return null;

    return (
        <Modal
            title={sprintf(__('Toggle Bar Designer — %s', 'imedia-menu'), slug)}
            onRequestClose={onClose}
            className="imm-toggle-bar-designer-modal"
            style={{ width: '90%', maxWidth: '1100px' }}
        >
            {loading ? (
                <div className="imedia-settings-loading">
                    <Spinner />
                    <p>{__('Loading toggle bar...', 'imedia-menu')}</p>
                </div>
            ) : (
                <>
                    <p className="description">
                        {__(
                            'Configure the mobile toggle bar with left/center/right regions. Drag blocks between regions or reorder them within a region.',
                            'imedia-menu'
                        )}
                    </p>

                    {message && (
                        <Notice
                            status={message.type}
                            isDismissible
                            onRemove={() => setMessage(null)}
                            className="imedia-notice"
                        >
                            {message.text}
                        </Notice>
                    )}

                    {editingBlock ? (
                        <BlockSettingsPanel
                            block={editingBlock}
                            onChange={(updated) => {
                                setEditingBlock(updated);
                                updateBlock(updated.id, updated);
                            }}
                            onClose={() => setEditingBlock(null)}
                        />
                    ) : (
                        <>
                            <div className="imm-toggle-bar-picker">
                                <h4>{__('Add Block', 'imedia-menu')}</h4>
                                <Flex gap={2} wrap="wrap">
                                    {BLOCK_TYPES.map((bt) => (
                                        <FlexItem key={bt.type}>
                                            <Button
                                                variant="secondary"
                                                onClick={() => addBlock(bt.type)}
                                            >
                                                <span style={{ marginRight: '4px' }}>{bt.icon}</span>
                                                {bt.label}
                                            </Button>
                                        </FlexItem>
                                    ))}
                                </Flex>
                            </div>

                            <div className="imm-toggle-bar-regions">
                                {ALIGNS.map((align) => {
                                    const regionBlocks = blocksByAlign(align.value);
                                    return (
                                        <div
                                            key={align.value}
                                            className={`imm-toggle-bar-region imm-toggle-bar-region--${align.value}`}
                                            onDragOver={handleDragOver}
                                            onDrop={(e) => handleDrop(e, blocks.length)}
                                        >
                                            <h4>{align.label}</h4>
                                            {regionBlocks.length === 0 && (
                                                <p className="description">{__('No blocks. Drag blocks here.', 'imedia-menu')}</p>
                                            )}
                                            {regionBlocks.map((block) => {
                                                const globalIndex = blocks.findIndex((b) => b.id === block.id);
                                                return (
                                                    <div
                                                        key={block.id}
                                                        draggable
                                                        onDragStart={(e) => handleDragStart(e, globalIndex)}
                                                        onDragOver={handleDragOver}
                                                        onDrop={(e) => handleDrop(e, globalIndex)}
                                                    >
                                                        <DraggableBlock
                                                            block={block}
                                                            index={globalIndex}
                                                            onEdit={() => setEditingBlock(block)}
                                                            onDelete={() => deleteBlock(block.id)}
                                                            onMoveLeft={() => moveBlock(block.id, 'left')}
                                                            onMoveRight={() => moveBlock(block.id, 'right')}
                                                            canMoveLeft={globalIndex > 0}
                                                            canMoveRight={globalIndex < blocks.length - 1}
                                                        />
                                                    </div>
                                                );
                                            })}
                                            {regionBlocks.length > 0 && (
                                                <Button
                                                    variant="tertiary"
                                                    onClick={() => changeAlign('__all__', align.value)}
                                                    style={{ display: 'none' }}
                                                >
                                                    {__('Set region', 'imedia-menu')}
                                                </Button>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>

                            <div className="imm-toggle-bar-align-controls">
                                {blocks.map((block, idx) => (
                                    <FlexItem key={block.id}>
                                        <SelectControl
                                            value={block.align}
                                            options={ALIGNS}
                                            onChange={(v) => changeAlign(block.id, v)}
                                            hideLabelFromVision
                                            label={sprintf(__('Align block %d', 'imedia-menu'), idx + 1)}
                                        />
                                    </FlexItem>
                                ))}
                            </div>
                        </>
                    )}

                    <Flex justify="flex-start" gap={4} style={{ marginTop: '20px' }}>
                        <FlexItem>
                            <Button
                                variant="primary"
                                onClick={handleSave}
                                disabled={saving}
                                isBusy={saving}
                            >
                                {saving ? __('Saving...', 'imedia-menu') : __('Save Toggle Bar', 'imedia-menu')}
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button variant="secondary" onClick={onClose}>
                                {__('Close', 'imedia-menu')}
                            </Button>
                        </FlexItem>
                    </Flex>
                </>
            )}
        </Modal>
    );
}

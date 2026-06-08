import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Button, Tooltip, TextControl } from '@wordpress/components';
import { DndContext, closestCenter, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { SortableContext, verticalListSortingStrategy } from '@dnd-kit/sortable';
import SortableItem from '../common/SortableItem';
import BlockList from '../Block/BlockList';
import { STORE_NAME } from '../../data/constants';

export default function Column({ rowId, column }) {
    const dispatch = useDispatch(STORE_NAME);
    const isSelected = useSelect(
        (select) => select(STORE_NAME).getSelectedColumnId() === column.id,
        [column.id]
    );
    const config = useSelect(
        (select) => select(STORE_NAME).getConfig(),
        []
    );

    const sensors = useSensors(
        useSensor(PointerSensor, { activationConstraint: { distance: 8 } })
    );

    const handleSelect = () =>
        dispatch.setSelection(null, isSelected ? null : column.id, null);
    const handleRemove = () => dispatch.removeColumn(rowId, column.id);
    const handleAddBlock = () =>
        dispatch.openBlockPicker({ rowId, columnId: column.id });

    const handleSpanChange = (value) => {
        const n = Math.max(1, Math.min(12, parseInt(value, 10) || 1));
        dispatch.updateColumn(rowId, column.id, { span: n });
    };

    const handleBlockDragEnd = (event) => {
        const { active, over } = event;
        if (!over || active.id === over.id) return;
        const blocks = column.blocks;
        const oldIndex = blocks.findIndex((b) => b.id === active.id);
        const newIndex = blocks.findIndex((b) => b.id === over.id);
        if (oldIndex === -1 || newIndex === -1) return;
        const reordered = [...blocks];
        const [moved] = reordered.splice(oldIndex, 1);
        reordered.splice(newIndex, 0, moved);
        dispatch.reorderBlocks(rowId, column.id, reordered);
    };

    const isGrid = (config.layout_type || 'columns') === 'grid';
    const isFlyout = (config.layout_type || 'columns') === 'flyout';
    const widthLabel = isGrid
        ? `${column.span || 1}/12`
        : (column.width === 'auto' ? 'Auto' : `${column.width}/12`);

    return (
        <SortableItem
            id={column.id}
            className={`imm-column-wrapper ${isSelected ? 'is-selected' : ''}`}
        >
            <div className="imm-column" onClick={handleSelect} role="button" tabIndex={0}>
                <div className="imm-column-header">
                    <span className="imm-column-label">
                    </span>
                    <span className="imm-column-width">{widthLabel}</span>
                    {isGrid && !isFlyout && (
                        <TextControl
                            className="imm-column-span"
                            type="number"
                            min={1}
                            max={12}
                            value={String(column.span || 1)}
                            onChange={handleSpanChange}
                            label={__('Span (1-12)', 'imedia-menu')}
                            __nextHasNoMarginBottom
                        />
                    )}
                    <Tooltip text={__('Remove Column', 'imedia-menu')}>
                        <Button
                            icon="trash"
                            label={__('Remove Column', 'imedia-menu')}
                            onClick={handleRemove}
                            size="small"
                            isDestructive
                        />
                    </Tooltip>
                </div>
                <div className="imm-column-blocks">
                    <DndContext
                        sensors={sensors}
                        collisionDetection={closestCenter}
                        onDragEnd={handleBlockDragEnd}
                    >
                        <SortableContext
                            items={column.blocks.map((b) => b.id)}
                            strategy={verticalListSortingStrategy}
                        >
                            <BlockList
                                rowId={rowId}
                                columnId={column.id}
                                blocks={column.blocks}
                            />
                        </SortableContext>
                    </DndContext>
                    {column.blocks.length === 0 && (
                        <div
                            className="imm-block-dropzone"
                            onClick={handleAddBlock}
                            role="button"
                            tabIndex={0}
                        >
                            <span className="imm-dropzone-icon">+</span>
                            <span>{__('Add Block', 'imedia-menu')}</span>
                        </div>
                    )}
                </div>
                <div className="imm-column-footer">
                    <Button
                        icon="plus"
                        variant="link"
                        onClick={handleAddBlock}
                        size="small"
                    >
                        {__('Add Block', 'imedia-menu')}
                    </Button>
                </div>
            </div>
        </SortableItem>
    );
}

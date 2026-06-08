import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Button, Tooltip } from '@wordpress/components';
import { DndContext, closestCenter, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { SortableContext, horizontalListSortingStrategy } from '@dnd-kit/sortable';
import SortableItem from '../common/SortableItem';
import DragHandle from '../DragHandle';
import ColumnList from '../Column/ColumnList';
import { STORE_NAME } from '../../data/constants';

export default function Row({ row, index }) {
    const dispatch = useDispatch(STORE_NAME);
    const isSelected = useSelect(
        (select) => select(STORE_NAME).getSelectedRowId() === row.id,
        [row.id]
    );

    const sensors = useSensors(
        useSensor(PointerSensor, { activationConstraint: { distance: 8 } })
    );

    const handleRemove = () => dispatch.removeRow(row.id);
    const handleSelect = () =>
        dispatch.setSelection(null, null, isSelected ? null : row.id);
    const handleAddCol = () => dispatch.addColumn(row.id);

    const handleColDragEnd = (event) => {
        const { active, over } = event;
        if (!over || active.id === over.id) return;
        const cols = row.columns;
        const oldIndex = cols.findIndex((c) => c.id === active.id);
        const newIndex = cols.findIndex((c) => c.id === over.id);
        if (oldIndex === -1 || newIndex === -1) return;
        const reordered = [...cols];
        const [moved] = reordered.splice(oldIndex, 1);
        reordered.splice(newIndex, 0, moved);
        dispatch.reorderColumns(row.id, reordered);
    };

    return (
        <SortableItem id={row.id} className={`imm-row-wrapper ${isSelected ? 'is-selected' : ''}`}>
            <div className="imm-row-header" onClick={handleSelect} role="button" tabIndex={0}>
                <DragHandle />
                <span className="imm-row-label">
                    {__('Row', 'imedia-menu')} {index + 1}
                </span>
                <div className="imm-row-tools">
                    <Tooltip text={__('Add Column', 'imedia-menu')}>
                        <Button icon="plus" label={__('Add Column', 'imedia-menu')} onClick={handleAddCol} size="small" />
                    </Tooltip>
                    <Tooltip text={__('Remove Row', 'imedia-menu')}>
                        <Button icon="trash" label={__('Remove Row', 'imedia-menu')} onClick={handleRemove} size="small" isDestructive />
                    </Tooltip>
                </div>
            </div>
            <div className="imm-row-columns">
                <DndContext
                    sensors={sensors}
                    collisionDetection={closestCenter}
                    onDragEnd={handleColDragEnd}
                >
                    <SortableContext
                        items={row.columns.map((c) => c.id)}
                        strategy={horizontalListSortingStrategy}
                    >
                        <ColumnList
                            rowId={row.id}
                            columns={row.columns}
                        />
                    </SortableContext>
                </DndContext>
            </div>
        </SortableItem>
    );
}

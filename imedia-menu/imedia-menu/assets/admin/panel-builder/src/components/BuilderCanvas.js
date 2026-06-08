import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { DndContext, closestCenter, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { SortableContext, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { STORE_NAME } from '../data/constants';
import RowList from './Row/RowList';

export default function BuilderCanvas() {
    const rows = useSelect((select) => select(STORE_NAME).getRows(), []);
    const dispatch = useDispatch(STORE_NAME);

    const sensors = useSensors(
        useSensor(PointerSensor, { activationConstraint: { distance: 8 } })
    );

    const handleAddRow = () => dispatch.addRow();

    if (!rows || rows.length === 0) {
        return (
            <div className="imm-canvas">
                <div className="imm-canvas-empty">
                    <p>{__('No rows yet. Add your first row.', 'imedia-menu')}</p>
                    <Button variant="primary" onClick={handleAddRow}>
                        {__('Add Row', 'imedia-menu')}
                    </Button>
                </div>
            </div>
        );
    }

    const handleDragEnd = (event) => {
        const { active, over } = event;
        if (!over || active.id === over.id) return;

        const oldIndex = rows.findIndex((r) => r.id === active.id);
        const newIndex = rows.findIndex((r) => r.id === over.id);
        if (oldIndex === -1 || newIndex === -1) return;

        const reordered = [...rows];
        const [moved] = reordered.splice(oldIndex, 1);
        reordered.splice(newIndex, 0, moved);
        dispatch.reorderRows(reordered);
    };

    return (
        <div className="imm-canvas">
            <DndContext
                sensors={sensors}
                collisionDetection={closestCenter}
                onDragEnd={handleDragEnd}
            >
                <SortableContext
                    items={rows.map((r) => r.id)}
                    strategy={verticalListSortingStrategy}
                >
                    <RowList rows={rows} />
                </SortableContext>
            </DndContext>
            <div className="imm-canvas-footer">
                <Button icon="plus" variant="secondary" onClick={handleAddRow}>
                    {__('Add Row', 'imedia-menu')}
                </Button>
            </div>
        </div>
    );
}

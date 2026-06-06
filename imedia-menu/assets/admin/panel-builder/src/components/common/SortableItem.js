import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

export default function SortableItem({ id, children, className = '' }) {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging,
    } = useSortable({ id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        opacity: isDragging ? 0.4 : 1,
        position: 'relative',
    };

    return (
        <div
            ref={setNodeRef}
            style={style}
            className={`${className} ${isDragging ? 'is-dragging' : ''}`}
            {...attributes}
            {...listeners}
        >
            {children}
        </div>
    );
}

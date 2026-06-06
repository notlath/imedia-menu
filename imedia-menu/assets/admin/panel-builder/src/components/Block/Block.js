import { useSelect, useDispatch } from '@wordpress/data';
import { Button, Tooltip } from '@wordpress/components';
import SortableItem from '../common/SortableItem';
import { STORE_NAME } from '../../data/constants';
import { getBlockIcon, getBlockType } from '../../utils/block-registry';

export default function Block({ rowId, columnId, block }) {
    const dispatch = useDispatch(STORE_NAME);
    const isSelected = useSelect(
        (select) => select(STORE_NAME).getSelectedBlockId() === block.id,
        [block.id]
    );

    const blockType = getBlockType(block.type);
    const icon = blockType?.icon || 'marker';
    const title = blockType?.title || block.type;

    const handleSelect = (e) => {
        e.stopPropagation();
        dispatch.setSelection(
            isSelected ? null : block.id,
            columnId,
            null
        );
    };

    const handleRemove = (e) => {
        e.stopPropagation();
        dispatch.removeBlock(rowId, columnId, block.id);
    };

    const handleDuplicate = (e) => {
        e.stopPropagation();
        const cloned = {
            ...block,
            id: undefined,
        };
        dispatch.addBlock(rowId, columnId, block.type, { ...block.config });
    };

    return (
        <SortableItem
            id={block.id}
            className={`imm-block-item ${isSelected ? 'is-selected' : ''}`}
        >
            <div
                className="imm-block-inner"
                onClick={handleSelect}
                role="button"
                tabIndex={0}
            >
                <div className="imm-block-preview">
                    <span className={`dashicons dashicons-${icon}`} aria-hidden="true" />
                    <span className="imm-block-title">{title}</span>
                </div>
                <div className="imm-block-actions" onClick={(e) => e.stopPropagation()}>
                    <Tooltip text="Duplicate">
                        <Button
                            icon="admin-page"
                            label="Duplicate"
                            onClick={handleDuplicate}
                            size="small"
                        />
                    </Tooltip>
                    <Tooltip text="Remove">
                        <Button
                            icon="trash"
                            label="Remove"
                            onClick={handleRemove}
                            size="small"
                            isDestructive
                        />
                    </Tooltip>
                </div>
            </div>
        </SortableItem>
    );
}

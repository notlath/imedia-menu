import { useDispatch } from '@wordpress/data';
import { Button, Tooltip } from '@wordpress/components';
import { STORE_NAME } from '../../data/constants';

export default function BlockToolbar({ rowId, columnId, blockId }) {
    const dispatch = useDispatch(STORE_NAME);
    return (
        <div className="imm-block-toolbar">
            <Tooltip text="Remove Block">
                <Button
                    icon="trash"
                    label="Remove"
                    onClick={() => dispatch.removeBlock(rowId, columnId, blockId)}
                    size="small"
                    isDestructive
                />
            </Tooltip>
        </div>
    );
}

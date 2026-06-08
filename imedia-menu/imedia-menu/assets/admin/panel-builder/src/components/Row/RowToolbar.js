import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Button, Tooltip } from '@wordpress/components';
import { STORE_NAME } from '../../data/constants';

export default function RowToolbar({ rowId }) {
    const dispatch = useDispatch(STORE_NAME);
    return (
        <div className="imm-row-toolbar">
            <Button
                icon="trash"
                label={__('Remove Row', 'imedia-menu')}
                onClick={() => dispatch.removeRow(rowId)}
                size="small"
                isDestructive
            />
        </div>
    );
}

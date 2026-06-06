import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { SelectControl, Button } from '@wordpress/components';
import { STORE_NAME } from '../../data/constants';

const WIDTH_OPTIONS = [
    { label: __('Auto', 'imedia-menu'), value: 'auto' },
    { label: '1/12', value: '1' },
    { label: '2/12', value: '2' },
    { label: '3/12', value: '3' },
    { label: '4/12', value: '4' },
    { label: '5/12', value: '5' },
    { label: '6/12', value: '6' },
    { label: '7/12', value: '7' },
    { label: '8/12', value: '8' },
    { label: '9/12', value: '9' },
    { label: '10/12', value: '10' },
    { label: '11/12', value: '11' },
    { label: '12/12', value: '12' },
];

export default function ColumnToolbar({ rowId, columnId, currentWidth }) {
    const dispatch = useDispatch(STORE_NAME);
    return (
        <div className="imm-col-toolbar">
            <SelectControl
                value={currentWidth}
                options={WIDTH_OPTIONS}
                onChange={(width) =>
                    dispatch.updateColumn(rowId, columnId, { width })
                }
                size="small"
            />
            <Button
                icon="trash"
                label={__('Remove Column', 'imedia-menu')}
                onClick={() => dispatch.removeColumn(rowId, columnId)}
                isDestructive
                size="small"
            />
        </div>
    );
}

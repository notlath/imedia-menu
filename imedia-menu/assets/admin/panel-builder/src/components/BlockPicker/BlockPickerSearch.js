import { SearchControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function BlockPickerSearch({ value, onChange }) {
    return (
        <SearchControl
            value={value}
            onChange={onChange}
            placeholder={__('Search blocks...', 'imedia-menu')}
        />
    );
}

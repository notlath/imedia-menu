import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function SettingsHeader({ isDirty, isSaving, onSave }) {
    return (
        <div className="imedia-settings-header">
            <h1 className="wp-heading-inline">
                {__('iMedia Menu Settings', 'imedia-menu')}
            </h1>

            <span className="imedia-settings-status">
                {isSaving && (
                    <span className="imedia-settings-saving">
                        {__('Saving...', 'imedia-menu')}
                    </span>
                )}
                {!isSaving && isDirty && (
                    <span className="imedia-settings-dirty">
                        {__('Unsaved changes', 'imedia-menu')}
                    </span>
                )}
                {!isSaving && !isDirty && (
                    <span className="imedia-settings-saved">
                        {__('All changes saved', 'imedia-menu')}
                    </span>
                )}
            </span>

            <Button
                variant="primary"
                onClick={onSave}
                disabled={isSaving || !isDirty}
            >
                {isSaving
                    ? __('Saving...', 'imedia-menu')
                    : __('Save Settings', 'imedia-menu')}
            </Button>
        </div>
    );
}

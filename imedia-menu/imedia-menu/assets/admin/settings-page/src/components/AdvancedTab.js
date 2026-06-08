import { __ } from '@wordpress/i18n';
import { ToggleControl, Button } from '@wordpress/components';

export default function AdvancedTab({ settings, onChange }) {
    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        {__('Delete Data on Uninstall', 'imedia-menu')}
                    </th>
                    <td>
                        <ToggleControl
                            label={__(
                                'Remove all plugin data when deleting the plugin',
                                'imedia-menu'
                            )}
                            checked={settings.delete_data_on_uninstall ?? false}
                            onChange={(value) =>
                                onChange({ delete_data_on_uninstall: value })
                            }
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Export Settings', 'imedia-menu')}</th>
                    <td>
                        <Button
                            variant="secondary"
                            onClick={() => {
                                const blob = new Blob(
                                    [JSON.stringify(settings, null, 2)],
                                    { type: 'application/json' }
                                );
                                const url = URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'imedia-menu-settings.json';
                                a.click();
                                URL.revokeObjectURL(url);
                            }}
                        >
                            {__('Download Export JSON', 'imedia-menu')}
                        </Button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Import Settings', 'imedia-menu')}</th>
                    <td>
                        <input
                            type="file"
                            accept=".json"
                            className="imedia-import-input"
                            onChange={(e) => {
                                const file = e.target.files[0];
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = (event) => {
                                    try {
                                        const imported = JSON.parse(
                                            event.target.result
                                        );
                                        onChange(imported);
                                    } catch {
                                        // invalid JSON
                                    }
                                };
                                reader.readAsText(file);
                            }}
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    );
}

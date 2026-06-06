import { __ } from '@wordpress/i18n';
import {
    SelectControl,
    ToggleControl,
    TextControl,
} from '@wordpress/components';

export default function IconsTab({ settings, onChange }) {
    const providers = settings.icon_providers ?? {};

    const updateProvider = (key, value) => {
        onChange({
            icon_providers: {
                ...providers,
                [key]: value,
            },
        });
    };

    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">{__('Icon Providers', 'imedia-menu')}</th>
                    <td>
                        <fieldset>
                            <ToggleControl
                                label={__(
                                    'Dashicons (WordPress core)',
                                    'imedia-menu'
                                )}
                                checked={providers.dashicons ?? true}
                                onChange={(value) =>
                                    updateProvider('dashicons', value)
                                }
                            />
                            <ToggleControl
                                label={__('Font Awesome', 'imedia-menu')}
                                checked={providers.fontawesome ?? false}
                                onChange={(value) =>
                                    updateProvider('fontawesome', value)
                                }
                            />
                            <ToggleControl
                                label={__(
                                    'Custom SVG Uploads',
                                    'imedia-menu'
                                )}
                                checked={providers.custom_svg ?? false}
                                onChange={(value) =>
                                    updateProvider('custom_svg', value)
                                }
                            />
                        </fieldset>
                        <p className="description">
                            {__(
                                'Select which icon sources are available when choosing icons for menu items.',
                                'imedia-menu'
                            )}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        {__('Font Awesome Source', 'imedia-menu')}
                    </th>
                    <td>
                        <SelectControl
                            value={settings.fontawesome_source ?? 'cdn'}
                            options={[
                                {
                                    label: 'CDN (jsDelivr)',
                                    value: 'cdn',
                                },
                                {
                                    label: 'Local (theme or plugin)',
                                    value: 'local',
                                },
                            ]}
                            onChange={(value) =>
                                onChange({ fontawesome_source: value })
                            }
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        {__('Font Awesome Version', 'imedia-menu')}
                    </th>
                    <td>
                        <TextControl
                            value={settings.fontawesome_version ?? '6.5.1'}
                            placeholder="6.5.1"
                            onChange={(value) =>
                                onChange({ fontawesome_version: value })
                            }
                        />
                        <p className="description">
                            {__(
                                'Version number used for the CDN URL (only used when CDN source is selected).',
                                'imedia-menu'
                            )}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        {__('Font Awesome CDN URL', 'imedia-menu')}
                    </th>
                    <td>
                        <TextControl
                            value={settings.fontawesome_cdn_url ?? ''}
                            placeholder="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css"
                            onChange={(value) =>
                                onChange({ fontawesome_cdn_url: value })
                            }
                        />
                        <p className="description">
                            {__(
                                'Override the default CDN URL. Leave empty to use the jsDelivr default based on the version above.',
                                'imedia-menu'
                            )}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    );
}

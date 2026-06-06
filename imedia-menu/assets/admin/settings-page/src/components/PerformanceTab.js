import { __ } from '@wordpress/i18n';
import {
    ToggleControl,
    RangeControl,
} from '@wordpress/components';

export default function PerformanceTab({ settings, onChange }) {
    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">{__('Enable Caching', 'imedia-menu')}</th>
                    <td>
                        <ToggleControl
                            label={__(
                                'Cache rendered menus for better performance',
                                'imedia-menu'
                            )}
                            checked={settings.enable_caching ?? true}
                            onChange={(value) =>
                                onChange({ enable_caching: value })
                            }
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Cache Duration', 'imedia-menu')}</th>
                    <td>
                        <RangeControl
                            value={settings.cache_duration ?? 60}
                            min={1}
                            max={1440}
                            onChange={(value) =>
                                onChange({ cache_duration: value })
                            }
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Code Splitting', 'imedia-menu')}</th>
                    <td>
                        <ToggleControl
                            label={__(
                                'Load only the assets needed for each page',
                                'imedia-menu'
                            )}
                            checked={settings.code_splitting ?? true}
                            onChange={(value) =>
                                onChange({ code_splitting: value })
                            }
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    );
}

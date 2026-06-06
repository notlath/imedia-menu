import { __ } from '@wordpress/i18n';
import {
    ToggleControl,
    SelectControl,
    RangeControl,
} from '@wordpress/components';

export default function GeneralTab({ settings, onChange }) {
    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">{__('Enable iMedia Menu', 'imedia-menu')}</th>
                    <td>
                        <ToggleControl
                            label={__(
                                'Replace WordPress menus with iMedia Menu on the frontend',
                                'imedia-menu'
                            )}
                            checked={settings.enabled ?? true}
                            onChange={(value) => onChange({ enabled: value })}
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Default Trigger Type', 'imedia-menu')}</th>
                    <td>
                        <SelectControl
                            value={settings.trigger_type ?? 'hover'}
                            options={[
                                { label: 'Hover', value: 'hover' },
                                { label: 'Click', value: 'click' },
                                { label: 'Hover + Click', value: 'hover_click' },
                            ]}
                            onChange={(value) => onChange({ trigger_type: value })}
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Hover Intent Delay', 'imedia-menu')}</th>
                    <td>
                        <RangeControl
                            value={settings.hover_delay ?? 200}
                            min={0}
                            max={500}
                            step={50}
                            onChange={(value) => onChange({ hover_delay: value })}
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Default Animation', 'imedia-menu')}</th>
                    <td>
                        <SelectControl
                            value={settings.default_animation ?? 'fade'}
                            options={[
                                { label: 'Fade', value: 'fade' },
                                { label: 'Slide Down', value: 'slide' },
                                { label: 'None', value: 'none' },
                            ]}
                            onChange={(value) => onChange({ default_animation: value })}
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Animation Duration', 'imedia-menu')}</th>
                    <td>
                        <RangeControl
                            value={settings.animation_duration ?? 200}
                            min={0}
                            max={1000}
                            step={50}
                            onChange={(value) => onChange({ animation_duration: value })}
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Admin Bar Preview Link', 'imedia-menu')}</th>
                    <td>
                        <ToggleControl
                            label={__(
                                'Show iMedia Menu link in the admin bar',
                                'imedia-menu'
                            )}
                            checked={settings.admin_bar_preview ?? true}
                            onChange={(value) => onChange({ admin_bar_preview: value })}
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    );
}

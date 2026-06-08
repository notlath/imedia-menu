import { __ } from '@wordpress/i18n';
import { SelectControl, ToggleControl } from '@wordpress/components';

export default function AnimationsTab({ settings, onChange }) {
    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">{__('Animation Easing', 'imedia-menu')}</th>
                    <td>
                        <SelectControl
                            value={settings.animation_easing ?? 'ease'}
                            options={[
                                { label: 'Ease', value: 'ease' },
                                { label: 'Ease In', value: 'ease-in' },
                                { label: 'Ease Out', value: 'ease-out' },
                                { label: 'Ease In-Out', value: 'ease-in-out' },
                            ]}
                            onChange={(value) =>
                                onChange({ animation_easing: value })
                            }
                        />
                        <p className="description">
                            {__(
                                'CSS easing function for panel open/close animations.',
                                'imedia-menu'
                            )}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Reduced Motion', 'imedia-menu')}</th>
                    <td>
                        <ToggleControl
                            label={__(
                                'Respect prefers-reduced-motion and disable animations',
                                'imedia-menu'
                            )}
                            checked={settings.reduced_motion ?? true}
                            onChange={(value) =>
                                onChange({ reduced_motion: value })
                            }
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    );
}

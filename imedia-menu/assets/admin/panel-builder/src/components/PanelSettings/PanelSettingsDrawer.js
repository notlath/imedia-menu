import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { SelectControl, TextControl, ColorPalette } from '@wordpress/components';
import { STORE_NAME } from '../../data/constants';

const WIDTH_OPTIONS = [
    { label: __('Container', 'imedia-menu'), value: 'container' },
    { label: __('Full Width', 'imedia-menu'), value: 'full' },
    { label: __('Custom', 'imedia-menu'), value: 'custom' },
];

const ANIMATION_OPTIONS = [
    { label: __('Fade', 'imedia-menu'), value: 'fade' },
    { label: __('Slide Down', 'imedia-menu'), value: 'slide' },
    { label: __('None', 'imedia-menu'), value: 'none' },
];

export default function PanelSettingsDrawer() {
    const { isOpen, config, styles } = useSelect((select) => ({
        isOpen: select(STORE_NAME).getPanelSettingsOpen(),
        config: select(STORE_NAME).getConfig(),
        styles: select(STORE_NAME).getStyles(),
    }), []);
    const dispatch = useDispatch(STORE_NAME);

    if (!isOpen) return null;

    return (
        <div className="imm-panel-settings-drawer">
            <div className="imm-drawer-header">
                <h3>{__('Panel Settings', 'imedia-menu')}</h3>
                <button
                    type="button"
                    onClick={() => dispatch.togglePanelSettings()}
                    className="imm-drawer-close"
                    aria-label={__('Close', 'imedia-menu')}
                >
                    &times;
                </button>
            </div>
            <div className="imm-drawer-body">
                <SelectControl
                    label={__('Panel Width', 'imedia-menu')}
                    value={config.panel_width || 'container'}
                    options={WIDTH_OPTIONS}
                    onChange={(value) =>
                        dispatch.updatePanelConfig({ panel_width: value })
                    }
                />
                {config.panel_width === 'custom' && (
                    <TextControl
                        label={__('Custom Width', 'imedia-menu')}
                        type="text"
                        value={config.custom_width || ''}
                        onChange={(value) =>
                            dispatch.updatePanelConfig({ custom_width: value })
                        }
                    />
                )}
                <SelectControl
                    label={__('Animation', 'imedia-menu')}
                    value={config.animation_type || 'fade'}
                    options={ANIMATION_OPTIONS}
                    onChange={(value) =>
                        dispatch.updatePanelConfig({ animation_type: value })
                    }
                />

                <hr />

                <div className="imm-field-row">
                    <label>{__('Panel Background Color', 'imedia-menu')}</label>
                    <ColorPalette
                        colors={[]}
                        value={(styles && styles.background && styles.background.color) || ''}
                        onChange={(value) =>
                            dispatch.updatePanelStyles({
                                background: {
                                    color: value || '',
                                },
                            })
                        }
                    />
                </div>

                <TextControl
                    label={__('Panel Padding', 'imedia-menu')}
                    help={__('e.g. 20px', 'imedia-menu')}
                    value={(styles && styles.padding && styles.padding.top) || ''}
                    onChange={(value) =>
                        dispatch.updatePanelStyles({
                            padding: {
                                top: value,
                                right: value,
                                bottom: value,
                                left: value,
                            },
                        })
                    }
                />

                <TextControl
                    label={__('Border Radius', 'imedia-menu')}
                    value={(styles && styles.border && styles.border.radius) || ''}
                    onChange={(value) =>
                        dispatch.updatePanelStyles({
                            border: { radius: value },
                        })
                    }
                />

                <TextControl
                    label={__('Box Shadow', 'imedia-menu')}
                    help={__('e.g. 0 2px 8px rgba(0,0,0,0.15)', 'imedia-menu')}
                    value={
                        styles && styles.shadow
                            ? `${styles.shadow.offsetX || '0'} ${styles.shadow.offsetY || '2px'} ${styles.shadow.blur || '8px'} ${styles.shadow.spread || ''} ${styles.shadow.color || 'rgba(0,0,0,0.15)'}`
                            : ''
                    }
                    onChange={(value) => {
                        const parts = value.split(/\s+/);
                        dispatch.updatePanelStyles({
                            shadow: {
                                offsetX: parts[0] || '0',
                                offsetY: parts[1] || '2px',
                                blur: parts[2] || '8px',
                                spread: parts[3] || '',
                                color: parts.slice(4).join(' ') || 'rgba(0,0,0,0.15)',
                            },
                        });
                    }}
                />
            </div>
        </div>
    );
}

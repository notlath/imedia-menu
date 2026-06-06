import { __ } from '@wordpress/i18n';
import { TextControl, ColorPalette, SelectControl } from '@wordpress/components';

export default function BlockStylesPanel({ styles, onChange }) {
    const doUpdate = (key, value) => {
        onChange({ ...styles, [key]: value });
    };

    return (
        <div className="imm-styles-panel">
            <div className="imm-field-row">
                <label>{__('Text Color', 'imedia-menu')}</label>
                <ColorPalette
                    colors={[]}
                    value={(styles && styles.color) || ''}
                    onChange={(value) => doUpdate('color', value || '')}
                />
            </div>
            <div className="imm-field-row">
                <label>{__('Background Color', 'imedia-menu')}</label>
                <ColorPalette
                    colors={[]}
                    value={(styles && styles.bg) || ''}
                    onChange={(value) => doUpdate('bg', value || '')}
                />
            </div>
            <TextControl
                label={__('Font Size', 'imedia-menu')}
                help={__('e.g. 14px, 1.2em', 'imedia-menu')}
                value={(styles && styles.fontSize) || ''}
                onChange={(value) => doUpdate('fontSize', value)}
            />
            <TextControl
                label={__('Padding', 'imedia-menu')}
                help={__('e.g. 10px 15px', 'imedia-menu')}
                value={(styles && styles.padding) || ''}
                onChange={(value) => doUpdate('padding', value)}
            />
            <TextControl
                label={__('Margin', 'imedia-menu')}
                help={__('e.g. 10px 0', 'imedia-menu')}
                value={(styles && styles.margin) || ''}
                onChange={(value) => doUpdate('margin', value)}
            />
            <TextControl
                label={__('Border Radius', 'imedia-menu')}
                help={__('e.g. 4px', 'imedia-menu')}
                value={(styles && styles.borderRadius) || ''}
                onChange={(value) => doUpdate('borderRadius', value)}
            />
            <TextControl
                label={__('Width', 'imedia-menu')}
                help={__('e.g. 100%, 200px', 'imedia-menu')}
                value={(styles && styles.width) || ''}
                onChange={(value) => doUpdate('width', value)}
            />
            <TextControl
                label={__('Min Height', 'imedia-menu')}
                help={__('e.g. 100px', 'imedia-menu')}
                value={(styles && styles.minHeight) || ''}
                onChange={(value) => doUpdate('minHeight', value)}
            />
        </div>
    );
}

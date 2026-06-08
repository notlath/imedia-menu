import { __ } from '@wordpress/i18n';
import { TextControl, TextareaControl, Button } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

export default function BannerSettings({ config, onChange }) {
    return (
        <div className="imm-field-group">
            <MediaUploadCheck>
                <MediaUpload
                    onSelect={(media) =>
                        onChange({
                            config: {
                                ...config,
                                image_id: media.id,
                                alt: media.alt || config.alt,
                            },
                        })
                    }
                    allowedTypes={['image']}
                    value={config.image_id}
                    render={({ open }) => (
                        <div className="imm-media-upload">
                            <Button variant="secondary" onClick={open} size="small">
                                {config.image_id
                                    ? __('Change Image', 'imedia-menu')
                                    : __('Select Image', 'imedia-menu')}
                            </Button>
                            {config.image_id > 0 && (
                                <Button
                                    variant="link"
                                    onClick={() =>
                                        onChange({
                                            config: { ...config, image_id: 0 },
                                        })
                                    }
                                    size="small"
                                    isDestructive
                                >
                                    {__('Remove', 'imedia-menu')}
                                </Button>
                            )}
                        </div>
                    )}
                />
            </MediaUploadCheck>
            <TextControl
                label={__('Title', 'imedia-menu')}
                value={config.title || ''}
                onChange={(value) => onChange({ config: { ...config, title: value } })}
            />
            <TextareaControl
                label={__('Description', 'imedia-menu')}
                value={config.text || ''}
                onChange={(value) => onChange({ config: { ...config, text: value } })}
            />
            <TextControl
                label={__('Button Text', 'imedia-menu')}
                value={config.button_text || ''}
                onChange={(value) => onChange({ config: { ...config, button_text: value } })}
            />
            <TextControl
                label={__('Link URL', 'imedia-menu')}
                type="url"
                value={config.link || ''}
                onChange={(value) => onChange({ config: { ...config, link: value } })}
            />
        </div>
    );
}

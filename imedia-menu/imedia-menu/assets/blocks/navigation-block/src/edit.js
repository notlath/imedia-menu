import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Spinner, Placeholder, Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import apiFetch from '@wordpress/api-fetch';

export default function Edit({ attributes, setAttributes, name }) {
    const { menuId } = attributes;
    const [menus, setMenus] = useState([]);
    const [isResolving, setIsResolving] = useState(true);

    useEffect(() => {
        apiFetch({ path: '/wp/v2/menus' })
            .then((data) => {
                setMenus(data ?? []);
                setIsResolving(false);
            })
            .catch(() => setIsResolving(false));
    }, []);

    const blockProps = useBlockProps();
    const options = [
        { value: 0, label: __('Select a menu\u2026', 'imedia-menu') },
        ...menus.map((m) => ({ value: m.id, label: m.name })),
    ];

    const selectControl = (
        <SelectControl
            label={__('Select Menu', 'imedia-menu')}
            value={menuId}
            options={options}
            onChange={(value) => setAttributes({ menuId: parseInt(value, 10) })}
        />
    );

    if (isResolving) {
        return (
            <div {...blockProps}>
                <Placeholder icon="menu" label="iMedia Menu">
                    <Spinner />
                </Placeholder>
            </div>
        );
    }

    if (menus.length === 0) {
        return (
            <div {...blockProps}>
                <Placeholder
                    icon="menu"
                    label="iMedia Menu"
                    instructions={__(
                        'No menus found. Create one under Appearance > Menus.',
                        'imedia-menu'
                    )}
                />
            </div>
        );
    }

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Menu Settings', 'imedia-menu')}>
                    {selectControl}
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                {menuId > 0 ? (
                    <Disabled>
                        <ServerSideRender block={name} attributes={attributes} />
                    </Disabled>
                ) : (
                    <Placeholder icon="menu" label="iMedia Menu">
                        {selectControl}
                    </Placeholder>
                )}
            </div>
        </>
    );
}

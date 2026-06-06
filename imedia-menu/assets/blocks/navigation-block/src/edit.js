import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Spinner, Placeholder } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

export default function Edit({ attributes, setAttributes }) {
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
    const selectedMenu = menus.find((m) => m.id === menuId);
    const options = [
        { value: 0, label: __('Select a menu…', 'imedia-menu') },
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
                <Placeholder icon="menu" label="iMedia Menu">
                    {menuId > 0 && selectedMenu ? (
                        <p className="imedia-menu-block-selected">
                            {__('Menu:', 'imedia-menu')} <strong>{selectedMenu.name}</strong>
                        </p>
                    ) : (
                        selectControl
                    )}
                </Placeholder>
            </div>
        </>
    );
}

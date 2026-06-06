import { useState, useMemo } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Button, SearchControl } from '@wordpress/components';
import { STORE_NAME } from '../../data/constants';
import { BLOCK_TYPES, BLOCK_CATEGORIES } from '../../utils/block-registry';
import { getDefaultConfig } from '../../utils/default-configs';

export default function BlockPicker() {
    const [search, setSearch] = useState('');
    const [activeCategory, setActiveCategory] = useState(null);

    const target = useSelect(
        (select) => select(STORE_NAME).getBlockPickerTarget(),
        []
    );
    const dispatch = useDispatch(STORE_NAME);

    const filteredBlocks = useMemo(() => {
        let result = BLOCK_TYPES;
        if (search) {
            const q = search.toLowerCase();
            result = result.filter(
                (b) =>
                    b.title.toLowerCase().includes(q) ||
                    b.type.toLowerCase().includes(q) ||
                    b.description.toLowerCase().includes(q)
            );
        }
        if (activeCategory) {
            result = result.filter((b) => b.category === activeCategory);
        }
        return result;
    }, [search, activeCategory]);

    const handleAddBlock = (blockType) => {
        if (!target) return;
        dispatch.addBlock(
            target.rowId,
            target.columnId,
            blockType,
            getDefaultConfig(blockType)
        );
    };

    const handleClose = () => dispatch.closeBlockPicker();

    return (
        <div className="imm-block-picker-overlay">
            <div className="imm-block-picker">
                <div className="imm-block-picker-header">
                    <h3>{__('Add Block', 'imedia-menu')}</h3>
                    <Button
                        icon="no"
                        label={__('Close', 'imedia-menu')}
                        onClick={handleClose}
                    />
                </div>

                <SearchControl
                    value={search}
                    onChange={setSearch}
                    placeholder={__('Search blocks...', 'imedia-menu')}
                />

                <div className="imm-block-picker-categories">
                    <Button
                        variant={activeCategory === null ? 'primary' : 'secondary'}
                        size="small"
                        onClick={() => setActiveCategory(null)}
                    >
                        {__('All', 'imedia-menu')}
                    </Button>
                    {BLOCK_CATEGORIES.map((cat) => (
                        <Button
                            key={cat.slug}
                            variant={activeCategory === cat.slug ? 'primary' : 'secondary'}
                            size="small"
                            onClick={() =>
                                setActiveCategory(
                                    activeCategory === cat.slug ? null : cat.slug
                                )
                            }
                        >
                            {cat.title}
                        </Button>
                    ))}
                </div>

                <div className="imm-block-picker-grid">
                    {filteredBlocks.map((block) => (
                        <button
                            key={block.type}
                            className="imm-block-picker-item"
                            onClick={() => handleAddBlock(block.type)}
                            type="button"
                        >
                            <span className={`dashicons dashicons-${block.icon}`} aria-hidden="true" />
                            <span className="imm-block-picker-item-title">
                                {block.title}
                            </span>
                            <span className="imm-block-picker-item-desc">
                                {block.description}
                            </span>
                        </button>
                    ))}
                </div>
            </div>
        </div>
    );
}

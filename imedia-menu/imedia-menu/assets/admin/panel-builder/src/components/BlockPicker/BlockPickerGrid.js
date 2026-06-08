import { getDefaultConfig } from '../../utils/default-configs';

export default function BlockPickerGrid({ blocks, onSelect }) {
    return (
        <div className="imm-block-picker-grid">
            {blocks.map((block) => (
                <button
                    key={block.type}
                    className="imm-block-picker-item"
                    onClick={() => onSelect(block.type)}
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
    );
}

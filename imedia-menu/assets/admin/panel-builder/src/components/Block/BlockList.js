import Block from './Block';

export default function BlockList({ rowId, columnId, blocks }) {
    return (
        <div className="imm-block-list">
            {blocks.map((block) => (
                <Block
                    key={block.id}
                    rowId={rowId}
                    columnId={columnId}
                    block={block}
                />
            ))}
        </div>
    );
}

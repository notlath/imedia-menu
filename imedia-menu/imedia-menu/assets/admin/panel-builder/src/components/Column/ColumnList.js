import Column from './Column';

export default function ColumnList({ rowId, columns }) {
    return (
        <div className="imm-column-list">
            {columns.map((column) => (
                <Column key={column.id} rowId={rowId} column={column} />
            ))}
        </div>
    );
}

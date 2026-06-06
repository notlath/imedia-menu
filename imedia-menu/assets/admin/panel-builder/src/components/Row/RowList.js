import Row from './Row';

export default function RowList({ rows }) {
    return (
        <div className="imm-row-list">
            {rows.map((row, index) => (
                <Row key={row.id} row={row} index={index} />
            ))}
        </div>
    );
}

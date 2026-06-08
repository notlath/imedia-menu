export default function ModalFrame({ children, onClose }) {
    return (
        <div className="imm-builder-modal-overlay" role="dialog" aria-modal="true">
            <div className="imm-builder-modal-backdrop" />
            <div className="imm-builder-modal-container">
                {children}
            </div>
        </div>
    );
}

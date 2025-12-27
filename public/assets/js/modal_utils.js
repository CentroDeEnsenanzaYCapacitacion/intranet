function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

function closeOnOverlay(event, modalId) {
    if (event.target.id === modalId) {
        closeModal(modalId);
    }
}

function openDocumentModal(documentId, documentName) {
    const documentIdField = document.getElementById('document_id');
    const modalTitle = document.getElementById('documentModalTitle');

    if (documentIdField) {
        documentIdField.value = documentId;
    }
    if (modalTitle) {
        modalTitle.textContent = 'Añadir documento: ' + documentName;
    }
    openModal('documentModal');
}

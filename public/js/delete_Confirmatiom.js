 document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        let deleteUrl = '';

        // Handle delete button clicks
        document.querySelectorAll('.delete-invoice-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                deleteUrl = btn.getAttribute('href');
                deleteModal.classList.remove('hidden');
            });
        });

        // Handle confirm delete
        confirmDeleteBtn.addEventListener('click', () => {
            if (deleteUrl) {
                window.location.href = deleteUrl;
            }
        });

        // Close modal
        document.querySelectorAll('.close-delete-modal, #deleteModalOverlay').forEach(el => {
            el.addEventListener('click', () => deleteModal.classList.add('hidden'));
        });
    });
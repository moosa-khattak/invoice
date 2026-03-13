 document.addEventListener('DOMContentLoaded', function() {
        const refundModal = document.getElementById('refundModal');
        const confirmRefundBtn = document.getElementById('confirmRefundBtn');
        let refundForm = null;

        // Open modal when any refund trigger button is clicked
        document.querySelectorAll('.refund-invoice-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                refundForm = btn.closest('form');
                refundModal.classList.remove('hidden');
            });
        });

        // Submit the stored form on confirm
        confirmRefundBtn.addEventListener('click', () => {
            if (refundForm) {
                refundForm.submit();
            }
        });

        // Close modal on cancel / overlay click
        document.querySelectorAll('.close-refund-modal, #refundModalOverlay').forEach(el => {
            el.addEventListener('click', () => refundModal.classList.add('hidden'));
        });
    });
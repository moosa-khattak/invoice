  document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('paymentModal');
        const confirmBtn = document.getElementById('confirmPayment');
        const amountField = document.getElementById('amountField');
        const partialInput = document.getElementById('partialAmount');
        const modalTitle = modal.querySelector('h3');
        const modalDesc = modal.querySelector('p');
        const confirmText = document.getElementById('confirmText');

        let activeInvoiceId = null;
        let activeStatus = null;

        // ── Status dropdowns ──────────────────────────────────────────────────────
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            const btn = dropdown.querySelector('.status-btn');
            const menu = dropdown.querySelector('.status-menu');
            const invoiceId = dropdown.dataset.invoiceId;

            // Toggle this dropdown; close all others
            if (btn) {
                btn.addEventListener('click', e => {
                    e.stopPropagation();
                    document.querySelectorAll('.status-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            }

            // Handle option clicks
            if (menu) {
                menu.querySelectorAll('.status-option').forEach(option => {
                    option.addEventListener('click', () => {
                        const newStatus = option.dataset.status;
                        const balance = option.dataset.balance;
                        const currency = option.dataset.currency;

                        menu.classList.add('hidden');
                        activeInvoiceId = invoiceId;
                        activeStatus = newStatus;

                        document.getElementById('modalError').classList.add('hidden');
                        document.getElementById('modalCurrency').textContent = currency;
                        document.getElementById('modalBalance').textContent = balance;

                        if (newStatus === 'Paid') {
                            modalTitle.textContent = 'Mark as Fully Paid';
                            modalDesc.textContent = 'This will set the balance to 0 and mark the invoice as Paid';
                            confirmText.textContent = 'Confirm & Mark as Paid';
                            amountField.classList.add('hidden');
                        } else if (newStatus === 'Partial') {
                            modalTitle.textContent = 'Record Partial Payment';
                            modalDesc.textContent = 'Enter the amount received to update the balance';
                            confirmText.textContent = 'Confirm Partial Payment';
                            amountField.classList.remove('hidden');
                            partialInput.value = balance;
                            partialInput.max = balance;
                        }

                        modal.classList.remove('hidden');
                    });
                });
            }
        });

        // ── Confirm button ────────────────────────────────────────────────────────
        confirmBtn.addEventListener('click', async () => {
            document.getElementById('modalError').classList.add('hidden');
            const amount = activeStatus === 'Partial' ? partialInput.value : null;
            await sendStatusUpdate(activeInvoiceId, activeStatus, amount);
        });

        // ── Close modal ───────────────────────────────────────────────────────────
        document.querySelectorAll('.close-modal, #modalOverlay').forEach(el => {
            el.addEventListener('click', () => modal.classList.add('hidden'));
        });

        // ── Close dropdowns on outside click ──────────────────────────────────────
        document.addEventListener('click', () => {
            document.querySelectorAll('.status-menu').forEach(m => m.classList.add('hidden'));
        });

        // ── Helpers ───────────────────────────────────────────────────────────────
        function showError(msg) {
            const el = document.getElementById('modalError');
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        async function sendStatusUpdate(invoiceId, status, amount = null) {
            try {
                const body = {
                    status
                };
                if (amount) body.amount = amount;

                const resp = await fetch(`/invoice/${invoiceId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(body),
                });

                if (resp.ok) {
                    window.location.reload();
                } else {
                    const data = await resp.json();
                    showError(data.message || 'Failed to update status.');
                }
            } catch (err) {
                console.error(err);
                showError('An error occurred. Please try again.');
            }
        }
    });
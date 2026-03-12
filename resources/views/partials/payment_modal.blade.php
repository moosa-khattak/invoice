{{-- Payment Modal Partial --}}
{{-- Includes both the modal HTML and all related JavaScript for marking as Paid --}}

<div id="paymentModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" id="modalOverlay"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 w-full max-w-lg">
            <div class="p-8 md:p-12">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Mark as Fully Paid</h3>
                        <p class="text-slate-500 font-medium text-sm mt-1">This will set the balance to 0 and mark the invoice as Paid</p>
                    </div>
                    <button type="button" class="close-modal cursor-pointer text-slate-400 hover:text-slate-900 transition-colors p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-5">
                    <p id="modalError" class="mt-2 text-[11px] text-rose-500 font-bold hidden"></p>

                    <div id="amountField" class="hidden">
                        <label class="block text-slate-700 font-bold text-sm mb-2 ml-1">Payment Amount</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span id="modalCurrency" class="text-slate-400 font-bold transition-colors">USD</span>
                            </div>
                            <input type="number" step="0.01" id="partialAmount"
                                class="block w-full pl-16 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-lg font-bold text-slate-800 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all"
                                placeholder="0.00">
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium mt-2 ml-1">Balance Due: <span id="modalBalance">0.00</span></p>
                    </div>

                    <button type="button" id="confirmPayment"
                        class="cursor-pointer w-full bg-slate-900 hover:bg-black text-white font-bold py-4 px-8 rounded-2xl shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="confirmText">Confirm & Mark as Paid</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
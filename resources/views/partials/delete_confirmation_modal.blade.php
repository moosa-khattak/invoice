{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" id="deleteModalOverlay"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 w-full max-w-md transform transition-all">
            <div class="p-8 md:p-10 text-center">
                <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-rose-100">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-slate-900 tracking-tighter mb-2">Delete Invoice?</h3>
                <p class="text-slate-500 font-medium text-sm mb-10">This action cannot be undone. All data associated with this invoice will be permanently removed.</p>

                <div class="flex flex-col gap-3">
                    <button type="button" id="confirmDeleteBtn"
                        class="cursor-pointer w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-rose-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <span>Delete Permanently</span>
                    </button>
                    <button type="button" class="close-delete-modal cursor-pointer w-full bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold py-4 px-8 rounded-2xl transition-all active:scale-[0.98]">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
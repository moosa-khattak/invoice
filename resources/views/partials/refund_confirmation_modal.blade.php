{{-- Refund Confirmation Modal --}}
<div id="refundModal" class="fixed inset-0 z-60 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" id="refundModalOverlay"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 w-full max-w-md transform transition-all">
            <div class="p-8 md:p-10 text-center">
                <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-purple-100">
                    <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-slate-900 tracking-tighter mb-2">Issue Refund?</h3>
                <p class="text-slate-500 font-medium text-sm mb-4">This will process a refund for the invoice. This action cannot be undone.</p>

                {{-- Refund Amount Display --}}
                <div class="bg-purple-50 border border-purple-100 rounded-2xl px-6 py-4 mb-6 text-center">
                    <p class="text-xs font-bold uppercase tracking-widest text-purple-400 mb-1">Refund Amount</p>
                    <p id="refundAmountDisplay" class="text-3xl font-black text-purple-700 tracking-tight">—</p>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Amount that was paid and will be returned</p>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="button" id="confirmRefundBtn"
                        class="cursor-pointer w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-purple-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        <span>Yes, Issue Refund</span>
                    </button>
                    <button type="button" class="close-refund-modal cursor-pointer w-full bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold py-4 px-8 rounded-2xl transition-all active:scale-[0.98]">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/refund_payment.js') }}"></script>
@endpush

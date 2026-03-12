@extends('layout.layout')
@section('title', 'Record Payment - ' . $invoice->invoice_number)
@section('content')

<div class="min-h-screen bg-slate-50/50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumbs / Back navigation --}}
        <div class="mb-8">
            <a href="{{ route('invoice.show', $invoice->invoice_number) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Invoice
            </a>
        </div>

        <div class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden">
            {{-- Professional Header --}}
            <div class="px-8 py-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-slate-50/50">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Record Cash Payment</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Record a manual payment for <span class="text-slate-900 font-bold">Invoice #{{ $invoice->invoice_number }}</span></p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Current Status</p>
                        <p class="text-sm font-bold text-slate-700">{{ $invoice->status }}</p>
                    </div>
                    <div class="h-10 w-px bg-slate-200 hidden sm:block"></div>
                    <div class="w-3 h-3 rounded-full {{ $invoice->status === 'Paid' ? 'bg-emerald-500' : ($invoice->status === 'Partial' ? 'bg-amber-500' : 'bg-rose-500') }} shadow-sm"></div>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {{-- Summary Column --}}
                    <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-r border-slate-100 pb-12 lg:pb-0 lg:pr-12">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Financial Summary</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                                <span class="text-slate-500 text-sm font-medium">Invoice Total</span>
                                <span class="text-slate-900 font-bold text-base">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                                <span class="text-slate-500 text-sm font-medium">Recorded Payments</span>
                                <span class="text-emerald-600 font-bold text-base">{{ $invoice->currency }} {{ number_format($invoice->amount_paid, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-4 bg-slate-900 rounded-xl px-5 mt-6 shadow-md">
                                <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Balance Due</span>
                                <div class="text-right">
                                    <span class="text-xl font-black text-white leading-none">{{ $invoice->currency }} {{ number_format($invoice->balance_due, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-start gap-3 p-4 bg-blue-50/50 rounded-xl border border-blue-100/50">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-[13px] text-blue-700 font-medium leading-relaxed">
                                Payments will be credited to the account instantly. The invoice status will update automatically based on the remaining balance.
                            </p>
                        </div>
                    </div>

                    {{-- Form Column --}}
                    <div class="lg:col-span-7">
                        <form action="{{ route('invoice.cash.process', $invoice->invoice_number) }}" method="POST">
                            @csrf
                            <div class="space-y-8">
                                <div>
                                    <label for="cash_amount" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Amount Received</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <span class="text-slate-400 font-bold text-lg">{{ $invoice->currency }}</span>
                                        </div>
                                        <input 
                                            type="number" 
                                            name="cash_amount" 
                                            id="cash_amount" 
                                            step="0.01" 
                                            min="0.01" 
                                            max="{{ $invoice->balance_due }}"
                                            placeholder="0.00"
                                            class="block w-full pl-16 pr-6 py-5 border-2 border-slate-100 rounded-2xl focus:border-slate-900 focus:ring-0 transition-all text-2xl font-bold text-slate-900 placeholder-slate-200 shadow-sm"
                                            required
                                            autofocus
                                            oninput="this.value = Math.abs(this.value) > {{ $invoice->balance_due }} ? {{ $invoice->balance_due }} : Math.abs(this.value)"
                                        >
                                    </div>
                                    @error('cash_amount')
                                        <p class="mt-3 text-sm text-rose-600 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full cursor-pointer bg-slate-900 hover:bg-slate-800 text-white font-bold py-5 px-8 rounded-2xl shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Add Payment to Ledger
                                    </button>
                                    <div class="mt-6 text-center">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Transaction will be logged for audits</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

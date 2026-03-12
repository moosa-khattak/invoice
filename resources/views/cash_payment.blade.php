@extends('layout.layout')

@section('title', 'Record Cash Payment - ' . $invoice->invoice_number)

@section('content')
<div class="max-w-xl mx-auto px-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden transform transition-all">

        {{-- Header Section --}}
        <div class="bg-slate-900 p-8 text-white relative h-48 flex flex-col justify-end">
            <div class="absolute top-8 left-8">
                <div class="bg-teal-500/20 text-teal-300 px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-2">
                    Payment Recording
                </div>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Record Cash Payment</h1>
            <p class="text-slate-400 mt-1 font-medium italic">Invoice #{{ $invoice->invoice_number }}</p>

            {{-- Decorative Element --}}
            <div class="absolute -right-8 -bottom-8 opacity-10">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.97 0-1.8 1.39-3.06 3.11-3.46V4h2.67v1.94c1.47.3 2.72 1.13 2.99 2.92h-2.04c-.21-.92-.81-1.39-2.08-1.39-1.34 0-2.31.57-2.31 1.6 0 .73.53 1.35 2.67 1.87 2.49.59 4.18 1.7 4.18 4.15 0 2.15-1.54 3.4-3.41 3.99z" />
                </svg>
            </div>
        </div>

        <div class="p-10 space-y-8">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Amount</span>
                    <span class="text-xl font-bold text-slate-800">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span>
                </div>
                <div class="p-4 bg-teal-50 rounded-2xl border border-teal-100/50">
                    <span class="block text-[10px] font-bold text-teal-600 uppercase tracking-widest mb-1">Balance Due</span>
                    <span class="text-xl font-bold text-teal-700">{{ $invoice->currency }} {{ number_format($invoice->balance_due, 2) }}</span>
                </div>
            </div>

            {{-- Form Section --}}
            <form action="{{ route('invoice.cash.process', $invoice->invoice_number) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="cash_amount" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Cash Received Amount</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-400 font-bold group-focus-within:text-teal-500 transition-colors">{{ $invoice->currency }}</span>
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            name="cash_amount"
                            id="cash_amount"
                            value="{{ old('cash_amount', $invoice->balance_due) }}"
                            max="{{ $invoice->balance_due }}"
                            min="0.01"
                            class="block w-full pl-16 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-lg font-bold text-slate-800 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder-slate-300"
                            placeholder="0.00"
                            required>
                    </div>
                    @error('cash_amount')
                    <p class="mt-2 text-sm text-red-500 font-medium ml-1">
                        <span class="inline-block w-1 h-1 bg-red-500 rounded-full mr-1 mb-1"></span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button
                        type="submit"
                        class="w-full cursor-pointer bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-slate-900/10 transition-all duration-300 transform hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm Cash Receipt
                    </button>

                    <a
                        href="{{ route('invoice.show', $invoice->invoice_number) }}"
                        class="w-full text-center text-slate-400 hover:text-slate-600 font-bold py-2 text-sm transition-colors">
                        Cancel and Go Back
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer Logic --}}
        <div class="bg-slate-50 border-t border-slate-100 p-6">
            <div class="flex items-center gap-4 text-slate-400">
                <div class="h-10 w-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xs font-medium leading-relaxed">
                    Recording a cash payment will update the invoice status to <span class="text-slate-600 font-bold">Paid</span> or <span class="text-slate-600 font-bold">Partial</span> based on the amount entered.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
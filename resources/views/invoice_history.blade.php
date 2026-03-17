@extends('layout.layout')
@section("title", "Invoice History - #" . $invoice->invoice_number)

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('allinvoices') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-4xl font-black text-slate-800 tracking-tighter">Activity History</h1>
                </div>
                <p class="text-slate-500 font-medium tracking-tight">Full transaction timeline for <span class="text-slate-900 font-bold">#{{ $invoice->invoice_number }}</span></p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('invoice.show', $invoice->invoice_number) }}" class="inline-flex items-center px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl hover:bg-slate-50 transition-all active:scale-95 shadow-sm gap-2">
                    View Invoice
                </a>
            </div>
        </div>

        <!-- Timeline Card -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="p-8 md:p-12">
                @if($transactions->isEmpty())
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-100">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">No activity yet</h3>
                        <p class="text-slate-400 font-medium">Transactions will appear here once payments or refunds are processed.</p>
                    </div>
                @else
                    <div class="relative">
                        <!-- Vertical Line -->
                        <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-100 hidden sm:block"></div>

                        <div class="space-y-12 mb-10">
                            @foreach($transactions as $transaction)
                                @php
                                    $isPayment = $transaction->type === 'payment';
                                    $colorClass = $isPayment ? 'emerald' : 'rose';
                                @endphp
                                <div class="relative flex flex-col sm:flex-row gap-6 sm:gap-10">
                                    <!-- Icon Dot -->
                                    <div class="relative z-10 shrink-0">
                                        <div class="w-10 h-10 rounded-2xl bg-{{ $colorClass }}-50 border border-{{ $colorClass }}-100 flex items-center justify-center text-{{ $colorClass }}-500 shadow-sm transition-transform hover:scale-110">
                                            @if($isPayment)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                            <div>
                                                <h4 class="text-lg font-black text-slate-900 tracking-tight">
                                                    {{ $isPayment ? 'Payment Received' : 'Refund Issued' }}
                                                </h4>
                                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                                    {{ $transaction->created_at->format('M d, Y • h:i A') }}
                                                </p>
                                            </div>
                                            <div class="text-left md:text-right">
                                                <p class="text-xl font-black text-slate-900 tracking-tighter">
                                                    {{ $isPayment ? '+' : '-' }}{{ $invoice->currency }} {{ number_format($transaction->amount, 2) }}
                                                </p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200/50">
                                                    {{ $transaction->payment_method ?? 'manual' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($transaction->notes || $transaction->transaction_id)
                                            <div class="mt-4 p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                                @if($transaction->notes)
                                                    <p class="text-sm font-medium text-slate-600 mb-2 italic">"{{ $transaction->notes }}"</p>
                                                @endif
                                                @if($transaction->transaction_id)
                                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                        <span>Ref ID:</span>
                                                        <span class="text-slate-600 select-all">{{ $transaction->transaction_id }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12 pt-8 border-t border-slate-100">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

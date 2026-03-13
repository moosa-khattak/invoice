@extends('layout.layout')
@section("title" , "All Invoices")
<meta name="csrf-token" content="{{ csrf_token() }}">


@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tighter mb-2">My Invoices</h1>
                <p class="text-slate-500 font-medium tracking-tight">Manage and track your business transactions</p>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative flex-1 md:w-72 group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search invoices..."
                        class="block w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all shadow-sm">
                </div>

                <a href="{{ route('invoice.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-black transition-all active:scale-95 shadow-lg shadow-slate-200 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create</span>
                </a>
            </div>
        </div>

        <!-- Invoices Grid/Table -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-500 border-b border-slate-100 ">
                            <th class="px-8 py-6 text-[14px] font-black text-slate-100 uppercase tracking-[0.2em]">Identifier</th>
                            <th class="px-8 py-6 text-[14px] font-black text-slate-100 uppercase tracking-[0.2em]">Client</th>
                            <th class="px-8 py-6 text-[14px] font-black text-slate-100 uppercase tracking-[0.2em]">Created</th>
                            <th class="px-8 py-6 text-[14px] font-black text-slate-100 uppercase tracking-[0.2em]">Amount</th>
                            <th class="px-8 py-6 text-[14px] font-black text-slate-100 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-6 text-[14px] font-black text-slate-100 uppercase tracking-[0.2em] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($invoices as $invoice)
                        <tr class="hover:bg-slate-50/40 transition-all duration-300 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0 border border-slate-200/50 group-hover:scale-110 transition-transform duration-500">
                                        @if($invoice->logo_path)
                                        <img src="{{ asset('storage/' . $invoice->logo_path) }}" class="w-6 h-6 object-contain">
                                        @else
                                        <span class="text-slate-400 font-bold text-xs uppercase">{{ substr($invoice->invoice_number, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <span class="text-slate-900 font-bold tracking-tight">#{{ $invoice->invoice_number ?? $invoice->id }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-900 font-bold uppercase text-xs tracking-wider mb-0.5">{{ explode("\n", $invoice->bill_to)[0] ?? 'N/A' }}</span>
                                    <span class="text-slate-400 text-[10px] font-medium truncate max-w-xs">{{ Str::limit(implode(' ', array_slice(explode("\n", $invoice->bill_to), 1)), 30) }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-slate-600 font-semibold text-sm">{{ $invoice->date ? $invoice->date->format('M d, Y') : 'N/A' }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-900 font-black tracking-tight">{{ $invoice->currency }} {{ number_format($invoice->total, 0) }}</span>
                                    <span class="text-[10px] font-bold {{ $invoice->balance_due > 0 ? 'text-rose-600' : 'text-emerald-600' }} uppercase tracking-wider">
                                        @if($invoice->balance_due > 0) Due: {{ number_format($invoice->balance_due, 0) }} @else Fully Paid @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="relative status-dropdown" data-invoice-id="{{ $invoice->invoice_number }}">
                                    @php
                                    $currentStatus = ucfirst($invoice->status);
                                    $statusConfig = [
                                    'Paid' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100/50', 'dot' => 'bg-emerald-500'],
                                    'Partial' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100/50', 'dot' => 'bg-amber-500'],
                                    'Unpaid' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100/50', 'dot' => 'bg-rose-400'],
                                    'Pending' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100/50', 'dot' => 'bg-rose-400'],
                                    'Refunded' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'],
                                    ];
                                    $cfg = $statusConfig[$currentStatus] ?? $statusConfig['Unpaid'];
                                    $showDropdown = in_array($currentStatus, ['Unpaid', 'Partial']);

                                    $statusOptions = [];
                                    if ($showDropdown && $currentStatus !== 'Paid') {
                                    $statusOptions[] = 'Paid';
                                    }
                                    @endphp

                                    <button
                                        class="{{ $showDropdown ? 'status-btn' : '' }} inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm transition-all {{ $showDropdown ? 'hover:scale-105 active:scale-95 cursor-pointer' : 'cursor-default' }} {{ $cfg['bg'] }} {{ $cfg['text'] }} {{ $cfg['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }} {{ $showDropdown ? 'animate-pulse' : '' }}"></span>
                                        {{ $currentStatus }}
                                        @if($showDropdown)
                                        <svg class="w-2.5 h-2.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        @endif
                                    </button>

                                    @if($showDropdown && count($statusOptions))
                                    <div class="status-menu hidden absolute left-0 z-50 mt-2 w-36 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden py-1">
                                        @foreach($statusOptions as $opt)
                                        <button
                                            class="status-option cursor-pointer w-full text-left px-4 py-2.5 text-[10px] font-black uppercase tracking-widest flex items-center gap-2.5 transition-colors hover:bg-slate-50 text-slate-500"
                                            data-status="{{ $opt }}"
                                            data-balance="{{ $invoice->balance_due }}"
                                            data-currency="{{ $invoice->currency }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig[$opt]['dot'] }}"></span>
                                            {{ $opt }}
                                        </button>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('invoice.show', $invoice->invoice_number) }}"
                                        class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-teal-600 hover:border-teal-500 hover:bg-teal-50 transition-all active:scale-90" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('invoice.edit', $invoice->invoice_number) }}"
                                        class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50 transition-all active:scale-90" title="Edit Invoice">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('invoice.delete', $invoice->invoice_number) }}"
                                        class="delete-invoice-btn p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-500 hover:bg-rose-50 transition-all active:scale-90" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </a>
                                    @if($invoice->amount_paid > 0 && !in_array($invoice->status, ['Refunded']))
                                    <form action="{{ route('invoice.refund', $invoice->invoice_number) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" class="refund-invoice-btn p-2.5 cursor-pointer rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-purple-600 hover:border-purple-500 hover:bg-purple-50 transition-all active:scale-90" title="Issue Refund">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-4xl flex items-center justify-center mb-6 border border-slate-100">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">No invoices found</h3>
                                    <p class="text-slate-400 font-medium max-w-sm mx-auto mb-8">You haven't created any invoices yet. Start by creating your first entry to track your payments.</p>
                                    <a href="{{ route('invoice.create') }}" class="inline-flex items-center px-8 py-3 bg-teal-600 text-white font-bold rounded-2xl hover:bg-teal-700 transition-all shadow-lg shadow-teal-100">
                                        Create First Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Payment Modal (extracted to its own partial) --}}
@include('partials.payment_modal')

{{-- Delete Confirmation Modal --}}
@include('partials.delete_confirmation_modal')

{{-- Refund Confirmation Modal --}}
@include('partials.refund_confirmation_modal')

@endsection

@push('scripts')
<script src="{{ asset('js/allInvoiceFilter.js') }}"></script>
@endpush
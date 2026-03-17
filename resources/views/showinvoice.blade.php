@extends('layout.layout')
@section('title' , "View Invoice")
@section('content')
<div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Top Navigation / Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('allinvoices') }}" class="group flex items-center text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to List
                </a>
                <div class="h-4 w-px bg-slate-200"></div>
                <span class="text-sm font-bold text-slate-400">Invoice <span class="text-slate-900">#{{ $invoice->invoice_number }}</span></span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('invoice.pdf', $invoice->invoice_number) }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('invoice.edit', $invoice->invoice_number) }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit
                </a>
                @if($invoice->balance_due > 0.1)
                <a href="{{ route('invoice.cash', $invoice->invoice_number) }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Pay via Cash
                </a>
                <a href="{{ route('invoice.payment', $invoice->invoice_number) }}" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                    Pay via Card
                </a>
                @endif
                @if($invoice->amount_paid > 0 && !in_array($invoice->status, ['Refunded']))
                <form action="{{ route('invoice.refund', $invoice->invoice_number) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" class="refund-invoice-btn inline-flex items-center cursor-pointer px-5 py-2.5 bg-white border border-slate-200 text-purple-700 text-sm font-bold rounded-xl hover:bg-purple-50 transition-all shadow-sm" data-amount="{{ number_format($invoice->amount_paid, 2) }}" data-currency="{{ $invoice->currency }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Issue Refund
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Invoice Document -->
            <div class="lg:col-span-8">
                <div class="bg-white border border-slate-200 rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden">
                    <!-- Brand Strip -->
                    <div class="h-2 bg-indigo-600"></div>

                    <div class="p-8 md:p-12">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-16">
                            <div>
                                @if ($invoice->logo_path && file_exists(storage_path('app/public/' . $invoice->logo_path)))
                                <img src="{{ asset('storage/' . $invoice->logo_path) }}"
                                    alt="Company Logo" class="h-20 w-auto object-contain mb-6" />
                                @else
                                <div class="h-16 w-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-extrabold text-2xl mb-6 shadow-lg shadow-slate-200">
                                    {{ substr($invoice->invoice_number, 0, 1) ?: 'I' }}
                                </div>
                                @endif
                                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Invoice</h1>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.2em] mt-1">#{{ $invoice->invoice_number }}</p>
                            </div>

                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Amount</p>
                                <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</p>
                                <div class="mt-4">
                                    @php
                                    $calculatedStatus = $invoice->status === 'Refunded' ? 'Refunded' : ($invoice->balance_due <= 0.1 ? 'Paid' : ($invoice->balance_due < $invoice->total ? 'Partial' : 'Pending'));
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                        @if($calculatedStatus == 'Paid') bg-emerald-100 text-emerald-700 
                                        @elseif($calculatedStatus == 'Partial') bg-amber-100 text-amber-700
                                        @elseif($calculatedStatus == 'Refunded') bg-slate-200 text-slate-700
                                        @else bg-rose-100 text-rose-700 @endif">
                                                {{ $calculatedStatus }}
                                            </span>
                                </div>
                            </div>
                        </div>

                        <!-- Addressing -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12  pb-12 border-b border-slate-100">
                            <div>
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Billed To</h3>
                                <div class="text-slate-900 font-bold text-lg mb-1">{{ explode("\n", $invoice->bill_to)[0] }}</div>
                                <div class="text-slate-500 text-sm font-medium leading-relaxed whitespace-pre-line">
                                    {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->bill_to), 1)))) !!}
                                </div>
                            </div>

                            @if($invoice->ship_to)
                            <div>
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Shipped To</h3>
                                <div class="text-slate-900 font-bold text-lg mb-1">{{ explode("\n", $invoice->ship_to)[0] }}</div>
                                <div class="text-slate-500 text-sm font-medium leading-relaxed whitespace-pre-line">
                                    {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->ship_to), 1)))) !!}
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Items Table -->
                        <div class="mb-12 ">
                            <table class="w-full text-left ">
                                <thead>
                                    <tr class="border-b border-slate-200   bg-blue-500  ">
                                        <th class="py-4 px-2 text-[14px] font-black text-white uppercase tracking-widest">Item & Description</th>
                                        <th class="py-4 px-2 text-[14px] font-black text-white uppercase tracking-widest text-center">Qty</th>
                                        <th class="py-4 px-2 text-[14px] font-black text-white uppercase tracking-widest text-right">Price</th>
                                        <th class="py-4 px-2 text-[14px] font-black text-white uppercase tracking-widest text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($invoice->items as $item)
                                    <tr>
                                        <td class="py-6 px-2">
                                            <div class="text-slate-900 font-bold">{{ $item->name ?? 'Item' }}</div>
                                            <div class="text-slate-400 text-xs font-medium mt-0.5">Custom Product Service</div>
                                        </td>
                                        <td class="py-6 px-2 text-center text-slate-600 font-bold text-sm">{{ $item->quantity ?? 0 }}</td>
                                        <td class="py-6 px-2 text-right text-slate-600 font-bold text-sm">{{ $invoice->currency }} {{ number_format($item->rate ?? 0, 2) }}</td>
                                        <td class="py-6 px-2 text-right text-slate-900 font-black text-sm">{{ $invoice->currency }} {{ number_format($item->amount ?? 0, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="flex flex-col items-end gap-3 pt-6 border-t border-slate-200">
                            <div class="flex justify-between w-full max-w-[320px] text-sm text-slate-500 font-bold">
                                <span>Subtotal</span>
                                <span class="whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</span>
                            </div>

                            @if($invoice->discount > 0)
                            <div class="flex justify-between w-full max-w-[320px] text-sm text-rose-500 font-bold">
                                <span>Discount ({{ $invoice->discount_rate }}%)</span>
                                <span class="whitespace-nowrap">-{{ $invoice->currency }} {{ number_format($invoice->discount, 2) }}</span>
                            </div>
                            @endif

                            @if($invoice->tax > 0)
                            <div class="flex justify-between w-full max-w-[320px] text-sm text-slate-500 font-bold">
                                <span>Tax ({{ $invoice->tax_rate }}%)</span>
                                <span class="whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->tax, 2) }}</span>
                            </div>
                            @endif

                            <!-- shipping -->
                            @if($invoice->shipping > 0)
                            <div class="flex justify-between w-full max-w-[320px] text-sm text-slate-500 font-bold">
                                <span>Shipping</span>
                                <span class="whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->shipping, 2) }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between w-full max-w-[320px] pt-4 mt-2 border-t border-slate-100">
                                <span class="text-[12px] font-black text-slate-400 uppercase tracking-widest">Grand Total</span>
                                <span class="text-2xl font-black text-slate-900 whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span>
                            </div>

                            @if($invoice->amount_paid > 0)
                            <div class="flex justify-between w-full max-w-[320px] text-sm text-green-600 font-bold">
                                <span>Amount Paid</span>
                                <span class="whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->amount_paid, 2) }}</span>
                            </div>
                            <div class="flex justify-between w-full max-w-[320px] p-4 bg-slate-900 rounded-2xl mt-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Balance Due</span>
                                <span class="text-lg font-black text-white leading-none whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->balance_due, 2) }}</span>
                            </div>
                            @endif

                            @if($invoice->amount_refunded > 0)
                            <div class="flex justify-between w-full max-w-[320px] pt-4 mt-2 border-t border-slate-100 text-sm text-slate-600 font-bold">
                                <span>Amount Refunded</span>
                                <span class="whitespace-nowrap">{{ $invoice->currency }} {{ number_format($invoice->amount_refunded, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar Info -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Status & QR Card -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Payment Status</h3>
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            @php
                            $headlineStatus = $invoice->status === 'Refunded' ? 'Refunded' : ($invoice->balance_due <= 0.1 ? 'Completed' : ($invoice->balance_due < $invoice->total ? 'Partial' : 'Pending'));
                                    $statusDotColor = $invoice->status === 'Refunded' ? 'bg-slate-400' : ($invoice->balance_due <= 0.1 ? 'bg-emerald-500' : ($invoice->balance_due < $invoice->total ? 'bg-amber-500' : 'bg-rose-500'));
                                            @endphp
                                            <div class="text-2xl font-black text-slate-900 mb-1">
                                                {{ $headlineStatus }}
                                            </div>
                                            <div class="text-sm text-slate-500 font-medium">Payment is {{ strtolower($headlineStatus) }}</div>
                        </div>
                        <div class="w-3 h-3 rounded-full {{ $statusDotColor }} animate-pulse"></div>
                    </div>

                    @if($invoice->balance_due > 0)
                    <div class="bg-white p-2 inline-block rounded-lg">
                        {!! QrCode::size(120)->generate(route('invoice.payment', $invoice->invoice_number)) !!}
                    </div>

                    @endif
                </div>

                <!-- Invoice Details Sidebar -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm space-y-8">
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Invoice Dates</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-500 font-medium">Issued on</span>
                                <span class="text-sm text-slate-900 font-bold">{{ $invoice->date ? $invoice->date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-500 font-medium">Due by</span>
                                <span class="text-sm text-rose-500 font-bold">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    @if($invoice->po_number)
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Reference</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">P.O. Number</span>
                            <span class="text-sm text-slate-900 font-bold">{{ $invoice->po_number }}</span>
                        </div>
                    </div>
                    @endif

                    @if($invoice->notes)
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Additional Notes</h3>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed italic">
                            "{{ $invoice->notes }}"
                        </p>
                    </div>
                    @endif

                    @if($invoice->terms)
                    <div class="pt-6 border-t border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Terms</h3>
                        <p class="text-[10px] text-slate-400 font-medium leading-relaxed uppercase tracking-wider">
                            {{ $invoice->terms }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Refund Confirmation Modal --}}
@include('partials.refund_confirmation_modal')
@extends('layout.layout')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Back & Download Buttons -->
        <div class="mb-8 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <a
                href="{{ route('allinvoices') }}"
                class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors duration-200"
            >
                <svg
                    class="w-5 h-5 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    ></path>
                </svg>
                Back to All Invoices
            </a>

            <a
                href="{{ route('invoice.pdf', $invoice->invoice_number) }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 shadow-sm transition-all duration-200"
            >
                <svg
                    class="w-5 h-5 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"
                    ></path>
                </svg>
                Download PDF
            </a>
        </div>

        <!-- Invoice Paper Design -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200">
            <!-- Header -->
            <div class="p-8 md:p-12 pb-8">
                <div class="flex flex-col  md:flex-row justify-between items-start">
                    
                    <!-- Left: Logo -->
                    <div class="mb-8 md:mb-0">
                        @if ($invoice->logo_path && file_exists(storage_path('app/public/' . $invoice->logo_path)))
                            <img
                                src="{{ asset('storage/' . $invoice->logo_path) }}"
                                alt="Company Logo"
                                class="h-30 w-auto object-contain rounded-full"
                            />
                        @else
                            <div class="h-16 w-16 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold text-2xl border border-blue-100">
                                INV
                            </div>
                        @endif
                    </div>

                    <!-- Right: Invoice Title & Status -->
                    <div class="text-left md:text-right">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-blue-600 tracking-tight mb-2">
                            INVOICE
                        </h1>
                        <p class="text-lg font-medium text-gray-500 mb-3">
                            #{{ $invoice->invoice_number }}
                        </p>
                        
                        <div>
                            @if($invoice->balance_due == 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 uppercase tracking-widest">
                                    Paid in Full
                                </span>
                            @elseif($invoice->balance_due < $invoice->total)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 uppercase tracking-widest">
                                    Partially Paid
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 uppercase tracking-widest">
                                    Unpaid
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Grid (Bill To, Ship To, Details) -->
            <div class="px-8 md:px-12 pb-">
                <div class="bg-gray-50/80 rounded-xl p-6 md:p-8 border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                      <div>
                          <!-- Col 1: Bill To -->
                        <div class = "">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                                Billed To
                            </h3>
                            <div class="text-gray-900 font-semibold text-lg mb-1">
                                {{ explode("\n", $invoice->bill_to)[0] ?? 'N/A' }}
                            </div>
                            <div class="text-gray-600 text-sm whitespace-pre-line leading-relaxed">
                                {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->bill_to), 1)))) !!}
                            </div>
                        </div>

                        <!-- Col 2: Ship To -->
                        <div>
                            @if ($invoice->ship_to)
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                                    Shipped To
                                </h3>
                                <div class="text-gray-900 font-semibold text-lg mb-1">
                                    {{ explode("\n", $invoice->ship_to)[0] ?? 'N/A' }}
                                </div>
                                <div class="text-gray-600 text-sm whitespace-pre-line leading-relaxed">
                                    {!! nl2br(e(implode("\n", array_slice(explode("\n", $invoice->ship_to), 1)))) !!}
                                </div>
                            @endif
                        </div>
                      </div>

                        <!-- Col 3: Invoice Details -->
                        <div class="md:text-right space-y-3">
                            <div class="flex justify-between md:justify-end md:space-x-4">
                                <span class="text-gray-500 text-sm">Invoice Date:</span>
                                <span class="text-gray-900 font-medium text-sm">
                                    {{ $invoice->date ? $invoice->date->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex justify-between md:justify-end md:space-x-4">
                                <span class="text-gray-500 text-sm">Due Date:</span>
                                <span class="text-gray-900 font-medium text-sm">
                                    {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                            @if ($invoice->po_number)
                                <div class="flex justify-between md:justify-end md:space-x-4">
                                    <span class="text-gray-500 text-sm">P.O. Number:</span>
                                    <span class="text-gray-900 font-medium text-sm">
                                        {{ $invoice->po_number }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="px-8 md:px-12 mb-8 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100/50">
                            <th class="py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-widest border-y border-gray-200 w-1/2">
                                Description
                            </th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-widest border-y border-gray-200 text-center w-1/6">
                                Quantity
                            </th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-widest border-y border-gray-200 text-right w-1/6">
                                Rate
                            </th>
                            <th class="py-3 px-4 text-xs font-bold text-gray-600 uppercase tracking-widest border-y border-gray-200 text-right w-1/6">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-b border-gray-200">
                        @if ($invoice->items && count($invoice->items) > 0)
                            @foreach ($invoice->items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-5 px-4 text-gray-900 font-medium text-sm">
                                        {{ $item['Item'] ?? 'Untitled Item' }}
                                    </td>
                                    <td class="py-5 px-4 text-center text-gray-600 text-sm">
                                        {{ $item['Quantity'] ?? 0 }}
                                    </td>
                                    <td class="py-5 px-4 text-right text-gray-600 text-sm whitespace-nowrap">
                                        {{ $invoice->currency }} {{ number_format($item['Rate'] ?? 0, 0) }}
                                    </td>
                                    <td class="py-5 px-4 text-right text-gray-900 font-semibold text-sm whitespace-nowrap">
                                        {{ $invoice->currency }} {{ number_format($item['Amount'] ?? 0, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500 italic">
                                    No items added to this invoice.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Totals Area -->
            <div class="px-8 md:px-12 pb-12 flex justify-end">
                <div class="w-full md:w-1/2 lg:w-2/5">
                    <div class="bg-gray-50/80 rounded-xl p-6 border border-gray-100 space-y-3">
                        
                        <!-- Subtotal -->
                        <div class="flex justify-between border-y border-gray-200 py-2 text-gray-600 text-sm">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900">
                                {{ $invoice->currency }} {{ number_format($invoice->subtotal, 0) }}
                            </span>
                        </div>
                        
                        <!-- Discount -->
                        @if ($invoice->discount > 0)
                            <div class="flex justify-between text-sm border-b border-gray-200 py-2">
                                <span class="text-gray-600">
                                    Discount ({{ $invoice->discount_rate }}%)
                                </span>
                                <span class="text-red-600 font-medium">
                                    -{{ $invoice->currency }} {{ number_format($invoice->discount, 0) }}
                                </span>
                            </div>
                        @endif

                        <!-- Tax -->
                        @if ($invoice->tax > 0)
                            <div class="flex justify-between text-gray-600 text-sm border-b border-gray-200 py-2">
                                <span>Tax ({{ $invoice->tax_rate }}%)</span>
                                <span class="font-medium text-gray-900">
                                    {{ $invoice->currency }} {{ number_format($invoice->tax, 0) }}
                                </span>
                            </div>
                        @endif

                        <!-- Shipping -->
                        @if ($invoice->shipping > 0)
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Shipping</span>
                                <span class="font-medium text-gray-900">
                                    {{ $invoice->currency }} {{ number_format($invoice->shipping, 0) }}
                                </span>
                            </div>
                        @endif

                        <div class="border-t border-gray-200 my-3"></div>

                        <!-- Total -->
                        <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                            <span>Total</span>
                            <span class="text-blue-600">
                                {{ $invoice->currency }} {{ number_format($invoice->total, 0) }}
                            </span>
                        </div>

                        <!-- Payments & Balance -->
                        @if ($invoice->amount_paid > 0)
                            <div class="border-t border-gray-200 mt-4 mb-3"></div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-extrabold text-lg">Amount Paid</span>
                                <span class="text-green-600 font-extrabold text-lg">
                                    {{ $invoice->currency }} {{ number_format($invoice->amount_paid, 0) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center text-base font-extrabold text-gray-900 pt-2">
                                <span>Balance Due</span>
                                <span>
                                    {{ $invoice->currency }} {{ number_format($invoice->balance_due, 0) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes & Terms (Bottom Section) -->
            @if ($invoice->notes || $invoice->terms)
                <div class="px-8 md:px-12 py-10 bg-gray-50 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                        
                        @if ($invoice->notes)
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                <h3 class="flex items-center text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Notes
                                </h3>
                                <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                                    {!! nl2br(e($invoice->notes)) !!}
                                </div>
                            </div>
                        @else
                            <div></div>
                        @endif

                        @if ($invoice->terms)
                            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                                <h3 class="flex items-center text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Terms & Conditions
                                </h3>
                                <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                                    {!! nl2br(e($invoice->terms)) !!}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif
            
        </div>
    </div>
@endsection

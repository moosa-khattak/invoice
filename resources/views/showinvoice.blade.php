@extends('layout.layout')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Back Button -->
        <div class="mb-8">
            <a
                href="{{ route('allinvoices') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition duration-150"
            >
                <svg
                    class="w-5 h-5 mr-1"
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
        </div>

        <!-- Invoice Paper Design -->
        <div
            class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100"
        >
            <!-- Logo & Header -->
            <div class="p-8 md:p-12 border-b border-gray-100 bg-gray-50/50">
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6"
                >
                    <div>
                        @if ($invoice->logo_path)
                            <img
                                src="{{ asset('storage/' . $invoice->logo_path) }}"
                                alt="Logo"
                                class="h-30 w-auto mb-6"
                            />
                        @else
                            <div
                                class="h-16 w-16 bg-slate-900 rounded-lg flex items-center justify-center text-white font-bold text-2xl mb-6"
                            >
                                INV
                            </div>
                        @endif
                        <h1
                            class="text-4xl font-extrabold text-gray-900 tracking-tight"
                        >
                            INVOICE
                        </h1>
                        <!-- <p class="text-gray-500 mt-1 font-medium">
                            {{ $invoice->invoice_number ?? '#' . $invoice->id }}
                        </p> -->
                    </div>
                    <div class="text-left md:text-right">
                        <div class="space-y-1">
                            <p
                                class="text-sm font-semibold text-gray-400 uppercase tracking-wider"
                            >
                                Amount Due
                            </p>
                            <p class="text-4xl font-bold text-slate-900">
                                {{ $invoice->currency }}
                                {{ number_format($invoice->balance_due, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                    <div class="space-y-8">
                        <div>
                            <h3
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest"
                            >
                                Bill To
                            </h3>
                            <div
                                class="text-gray-900 font-medium whitespace-pre-line"
                            >
                                {{ $invoice->bill_to ?? 'N/A' }}
                            </div>
                        </div>
                        @if ($invoice->ship_to)
                            <div>
                                <h3
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest"
                                >
                                    Ship To
                                </h3>
                                <div
                                    class="text-gray-900 font-medium whitespace-pre-line leading-relaxed"
                                >
                                    {{ $invoice->ship_to }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="md:text-right space-y-4">
                        <div class="flex flex-col md:items-end">
                            <span
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                            >
                                Invoice Date
                            </span>
                            <span class="text-gray-900 font-semibold">
                                {{ $invoice->date ? $invoice->date->format('F d, Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex flex-col md:items-end">
                            <span
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                            >
                                Due Date
                            </span>
                            <span class="font-semibold text-red-600">
                                {{ $invoice->due_date ? $invoice->due_date->format('F d, Y') : 'N/A' }}
                            </span>
                        </div>
                        @if ($invoice->po_number)
                            <div class="flex flex-col md:items-end">
                                <span
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                                >
                                    P.O. Number
                                </span>
                                <span class="text-gray-900 font-semibold">
                                    {{ $invoice->po_number }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-4">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="border-b-2 bg-black text-white border-slate-900"
                            >
                                <th
                                    class="py-4 px-2 font-bold uppercase text-xs tracking-widest"
                                >
                                    Description
                                </th>
                                <th
                                    class="py-4 px-2 font-bold uppercase text-xs tracking-widest text-center"
                                >
                                    Quantity
                                </th>
                                <th
                                    class="py-4 px-2 font-bold uppercase text-xs tracking-widest text-center"
                                >
                                    Rate
                                </th>
                                <th
                                    class="py-4 px-2 font-bold uppercase text-xs tracking-widest text-right"
                                >
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @if ($invoice->items)
                                @foreach ($invoice->items as $item)
                                    <tr>
                                        <td
                                            class="py-6 px-2 text-gray-900 font-medium"
                                        >
                                            {{ $item['Item'] ?? 'Untitled Item' }}
                                        </td>
                                        <td
                                            class="py-6 px-2 text-center text-gray-600"
                                        >
                                            {{ $item['Quantity'] ?? 0 }}
                                        </td>
                                        <td
                                            class="py-6 px-2 text-center text-gray-600"
                                        >
                                            {{ number_format($item['Rate'] ?? 0, 2) }}
                                        </td>
                                        <td
                                            class="py-6 px-2 text-right text-gray-900 font-bold"
                                        >
                                            {{ number_format($item['Amount'] ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="flex justify-end border-t-2 border-gray-100 pt-8">
                    <div class="w-full md:w-1/2 space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span class="font-medium">Subtotal</span>
                            <span>
                                {{ number_format($invoice->subtotal, 2) }}
                            </span>
                        </div>
                        @if ($invoice->discount > 0)
                            <div class="flex justify-between text-gray-600">
                                <span class="font-medium">
                                    Discount ({{ $invoice->discount_rate }}%)
                                </span>
                                <span class="text-red-500">
                                    -{{ number_format($invoice->discount, 2) }}
                                </span>
                            </div>
                        @endif

                        @if ($invoice->tax > 0)
                            <div class="flex justify-between text-gray-600">
                                <span class="font-medium">
                                    Tax ({{ $invoice->tax_rate }}%)
                                </span>
                                <span>
                                    {{ number_format($invoice->tax, 2) }}
                                </span>
                            </div>
                        @endif

                        @if ($invoice->shipping > 0)
                            <div class="flex justify-between text-gray-600">
                                <span class="font-medium">Shipping</span>
                                <span>
                                    {{ number_format($invoice->shipping, 2) }}
                                </span>
                            </div>
                        @endif

                        <div
                            class="flex justify-between text-xl font-bold text-slate-900 pt-3 border-t border-gray-100"
                        >
                            <span>Total</span>
                            <span>
                                {{ $invoice->currency }}
                                {{ number_format($invoice->total, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span class="font-medium">Amount Paid</span>
                            <span>
                                {{ number_format($invoice->amount_paid, 2) }}
                            </span>
                        </div>
                        <div
                            class="flex justify-between text-lg  p-2 font-extrabold text-slate-900 bg-gray-100 rounded-lg"
                        >
                            <span>Balance Due</span>
                            <span>
                                {{ $invoice->currency }}
                                {{ number_format($invoice->balance_due, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Notes & Terms -->
                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-12 text-center">
                    <div
                        class="bg-gray-50/50 p-6 rounded-xl border border-gray-100"
                    >
                        <h3
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4"
                        >
                            Notes
                        </h3>
                        <div
                            class="text-gray-700 text-sm leading-relaxed whitespace-pre-line"
                        >
                            {{ $invoice->notes ?: 'No notes provided' }}
                        </div>
                    </div>

                    <div
                        class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 text-center"
                    >
                        <h3
                            class="text-xs  font-bold text-gray-400 uppercase tracking-widest mb-4"
                        >
                            Terms & Conditions
                        </h3>
                        <div
                            class="text-gray-700 text-sm leading-relaxed whitespace-pre-line"
                        >
                            {{ $invoice->terms ?: 'No terms provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

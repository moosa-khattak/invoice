@extends('layout.layout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">All Invoices</h1>
            <a
                href="{{ route('invoice.create') }}"
                class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2 rounded-lg font-medium transition duration-200 shadow-sm"
            >
                Create New Invoice
            </a>
        </div>

        <!-- Invoices Table -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-100 text-gray-600 text-sm font-semibold uppercase tracking-wider"
                        >
                            <th class="px-6 py-4">Logo</th>
                            <th class="px-6 py-4">Invoice #</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Balance</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                            <th class="px-6 py-4 text-center">Delete</th>
                        </tr>   
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($invoices as $invoice)
                            <tr
                                class="hover:bg-gray-50 transition duration-150"
                            >
                                <td class="px-6 py-4">
                                    @if ($invoice->logo_path)
                                        <img
                                            src="{{ asset('storage/' . $invoice->logo_path) }}"
                                            alt="Logo"
                                            class="h-10 w-10 object-contain rounded border border-gray-100 bg-gray-50"
                                        />
                                    @else
                                        <div
                                            class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs font-bold"
                                        >
                                            N/A
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $invoice->invoice_number ?? '#' . $invoice->id }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $invoice->bill_to ?? 'N/A' }}
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 whitespace-nowrap"
                                >
                                    {{ $invoice->date ? $invoice->date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td
                                    class="px-6 py-4 font-semibold text-gray-900"
                                >
                                    {{ $invoice->currency }}
                                    {{ number_format($invoice->total, 2) }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ number_format($invoice->balance_due, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="{{ route('invoice.show', $invoice->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium bg-indigo-50 px-3 py-1 rounded-md transition"
                                    >
                                        View
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="{{ route('invoice.delete', $invoice->id) }}"
                                        class="text-red-600 hover:text-red-900 font-medium bg-red-50 px-3 py-1 rounded-md transition"
                                    >
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="7"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    <div class="flex flex-col items-center">
                                        <svg
                                            class="w-12 h-12 text-gray-200 mb-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        <p
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            No invoices found
                                        </p>
                                        <p class="text-gray-400">
                                            Get started by creating your first
                                            invoice.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

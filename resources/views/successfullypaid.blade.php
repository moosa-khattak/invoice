@extends('layout.layout')

@section('content')
<div class="min-h-screen bg-linear-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <!-- Success Header -->
        <div class="bg-emerald-600 text-white text-center py-8 px-6">
            <div class="flex justify-center mb-4">
                <div class="bg-white/20 p-4 rounded-full">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold">Payment Successful</h1>
            <p class="text-emerald-100 mt-2 text-sm">
                Your invoice has been paid successfully.
            </p>
        </div>

        <!-- Body -->
        <div class="p-8 text-center">

            <!-- Amount -->
            <div class="mb-6">
                <p class="text-gray-500 text-sm"> Amount Paid </p>
                <p class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $invoice->currency ?? 'USD' }} {{ number_format($amountPaid ?? $invoice->amount_paid ?? 0, 2) }}
                </p>

            </div>

            <!-- Invoice Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200 text-sm text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">Invoice #</span>
                    <span class="font-medium text-gray-800">
                        {{ $invoice->invoice_number }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-800 capitalize">
                        {{ ucwords(str_replace('_', ' ', $invoice->payment_method ?? 'N/A')) }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('invoice.show', $invoice->invoice_number) }}"
                    class="w-1/2 cursor-pointer py-3 px-4 bg-gray-100 hover:bg-gray-200 
                          text-gray-700 rounded-lg font-medium transition text-center">
                    View Invoice
                </a>

                <a href="{{ route('allinvoices') }}"
                    class="w-1/2 cursor-pointer py-3 px-4 bg-emerald-600 hover:bg-emerald-700 
                          text-white rounded-lg font-medium transition text-center">
                    Back to Invoices
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
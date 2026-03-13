@extends('layout.layout')
@section('title' , "Payment Form")
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-12 pb-24">
    <div class="w-full max-w-2xl bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">

        <!-- Header -->
        <div class="bg-slate-900 px-8 py-6 text-white text-center">
            <h1 class="text-2xl font-bold mb-2">Complete Payment</h1>
            <p class="text-slate-300">Please select a payment method to finalize your invoice</p>
        </div>

        <div class="p-8">
            <!-- Invoice Summary Info -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-4">Invoice Summary</h3>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Due Amount:</span>
                    <span class="text-xl font-bold text-teal-600">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->balance_due ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Bill To:</span>
                    <span class="text-gray-700 font-medium">{{ $invoice->bill_to ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Payment Method Selector -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Choose Payment Method</h3>
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="switchMethod('card')" id="btn-card" class="flex flex-col items-center justify-center p-4 border-2 border-indigo-600 bg-indigo-50 rounded-xl transition cursor-pointer">
                        <svg class="w-8 h-8 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span class="font-medium text-indigo-900">Credit Card</span>
                    </button>
                    <button type="button" onclick="switchMethod('bank')" id="btn-bank" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 hover:border-indigo-200 rounded-xl transition cursor-pointer">
                        <svg class="w-8 h-8 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                        </svg>
                        <span class="font-medium text-gray-600">Bank Transfer</span>
                    </button>
                </div>
            </div>

            <!-- Stripe Payment Form -->
            <div id="card-section">
                <form id="stripe-form" action="{{ route('invoice.payment.process', ['id' => $invoice->invoice_number]) }}" method="POST">
                    @csrf

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Card Details</h3>
                    <input type="hidden" name="payment_intent_id" id="payment-intent-id">
                    <div id="card-element" class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <!-- Stripe Card Element will be inserted here -->
                    </div>

                    <div id="card-errors" role="alert" class="hidden text-rose-500 text-sm mb-4 p-3 bg-rose-50 rounded-lg border border-rose-100"></div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('invoice.show', $invoice->invoice_number) }}" class="w-1/3 text-center py-3 px-4 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button id="submit-button" type="button" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-bold shadow-lg shadow-indigo-100 transition flex justify-center items-center gap-2 cursor-pointer">
                            <span id="button-text">Pay {{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->balance_due ?? 0, 2) }}</span>
                            <div id="spinner" class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bank Transfer Section -->
            <div id="bank-section" class="hidden">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Bank Account Details
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b border-blue-100 pb-2">
                            <span class="text-blue-700 text-sm transition-all duration-300 ease-in-out hover:scale-x-105 origin-left">Bank Name:</span>
                            <span class="text-blue-900 font-bold">International Commerce Bank</span>
                        </div>
                        <div class="flex justify-between border-b border-blue-100 pb-2">
                            <span class="text-blue-700 text-sm">Account Name:</span>
                            <span class="text-blue-900 font-bold">Invoice App Solutions</span>
                        </div>
                        <div class="flex justify-between border-b border-blue-100 pb-2">
                            <span class="text-blue-700 text-sm">Account Number:</span>
                            <span class="text-blue-900 font-bold text-lg tracking-wider">0987 6543 2101</span>
                        </div>
                        <div class="flex justify-between border-b border-blue-100 pb-2">
                            <span class="text-blue-700 text-sm">SWIFT/BIC:</span>
                            <span class="text-blue-900 font-bold uppercase">ICOMMGB2L</span>
                        </div>
                        <div class="flex justify-between pt-2">
                            <span class="text-blue-700 text-sm">Reference:</span>
                            <span class="text-blue-900 font-bold uppercase">{{ $invoice->invoice_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200 mb-8">
                    <p class="text-sm text-gray-600">Please include the <span class="font-bold text-gray-800">Invoice Number</span> as your payment reference.</p>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('invoice.show', $invoice->invoice_number) }}" class="w-full text-center py-3 px-4 bg-gray-800 hover:bg-black text-white rounded-lg font-bold transition flex justify-center items-center gap-2 cursor-pointer shadow-lg shadow-gray-200">
                        Back to Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/stripe_payment_method.js') }}"></script>
@endpush
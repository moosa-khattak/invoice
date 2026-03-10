@extends('layout.layout')

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

            <!-- Payment Options Form -->
            <form action="{{ route('invoice.payment.process', ['id' => $invoice->invoice_number]) }}" method="POST">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Payment Method</h3>

                <div class="space-y-4 mb-8">
                    <!-- Option 1: Credit Card Mock -->
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-teal-500 transition border-gray-200">
                        <input type="radio" name="payment_method" value="credit_card" class="h-5 w-5 text-teal-600" checked required>
                        <div class="ml-4 flex items-center gap-3">
                            <div class="bg-slate-100 p-2 rounded">
                                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="block font-medium text-gray-800">Credit / Debit Card</span>
                                <span class="block text-sm text-gray-500">Pay securely using your card</span>
                            </div>
                        </div>
                    </label>

                    <!-- Option 2: PayPal Mock -->
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-teal-500 transition border-gray-200">
                        <input type="radio" name="payment_method" value="paypal" class="h-5 w-5 text-teal-600">
                        <div class="ml-4 flex items-center gap-3">
                            <div class="bg-[#003087] p-2 rounded text-white font-bold italic w-10 flex justify-center">
                                P
                            </div>
                            <div>
                                <span class="block font-medium text-gray-800">PayPal</span>
                                <span class="block text-sm text-gray-500">You will be redirected to PayPal</span>
                            </div>
                        </div>
                    </label>

                    <!-- Option 3: Scan QR Code Mock -->
                    <!-- <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-teal-500 transition border-gray-200">
                        <input type="radio" name="payment_method" value="qr_code" class="h-5 w-5 text-teal-600">
                        <div class="ml-4 flex items-center justify-between w-full">
                            <div class="flex items-center gap-3">
                                <div class="bg-gray-800 p-2 rounded text-white font-bold w-10 flex justify-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 4h6v6H4zM20 4h-6v6h6zM4 20h6v-6H4zM20 14h-2v-2h-2v2h-2v2h2v2h-2v2h2v-2h2v2h2v-2h-2v-2h2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-medium text-gray-800">Pay via QR Code</span>
                                    <span class="block text-sm text-gray-500">Scan using your mobile banking app</span>
                                </div>
                            </div> -->
                    <!-- Mock QR API generation -->
                    <!-- <div class="hidden sm:block">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('invoice.payment', ['id' => $invoice->invoice_number])) }}" alt="Payment QR Code" class="w-20 h-20 rounded border border-gray-200 shadow-sm" />
                            </div> -->
                </div>
                </label>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('invoice.show', $invoice->invoice_number) }}" class="w-1/3 text-center py-3 px-4 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                Cancel Payment
            </a>
            <button type="submit" class="w-2/3 bg-teal-600 hover:bg-teal-700 text-white py-3 px-4 rounded-lg font-bold shadow-sm transition flex justify-center items-center gap-2 cursor-pointer">
                <span>Pay</span>
                <span>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->balance_due ?? 0, 2) }}</span>
            </button>
        </div>
        </form>
    </div>
</div>
</div>
@endsection
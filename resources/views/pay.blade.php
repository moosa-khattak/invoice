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

            <!-- Stripe Payment Form -->
            <form id="stripe-form" action="{{ route('invoice.payment.process', ['id' => $invoice->invoice_number]) }}" method="POST">
                @csrf

                <h3 class="text-lg font-semibold text-gray-800 mb-6">Payment Details</h3>
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
                    <button id="submit-button" type="button" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-bold shadow-lg shadow-indigo-100 transition flex justify-center items-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="button-text">Pay {{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->balance_due ?? 0, 2) }}</span>
                        <div id="spinner" class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();

    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const card = elements.create('card', {
        style: style
    });
    card.mount('#card-element');

    const form = document.getElementById('stripe-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const errorDisplay = document.getElementById('card-errors');

    submitButton.addEventListener('click', async (e) => {
        setLoading(true);

        const {
            paymentIntent,
            error
        } = await stripe.confirmCardPayment("{{ $clientSecret }}", {
            payment_method: {
                card: card,
                billing_details: {
                    name: "{{ addslashes($invoice->bill_to) }}"
                }
            }
        });

        if (error) {
            errorDisplay.textContent = error.message;
            errorDisplay.classList.remove('hidden');
            setLoading(false);
        } else {
            if (paymentIntent.status === 'succeeded') {
                document.getElementById('payment-intent-id').value = paymentIntent.id;
                form.submit();
            }
        }
    });

    function setLoading(isLoading) {
        if (isLoading) {
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');
        } else {
            submitButton.disabled = false;
            spinner.classList.add('hidden');
            buttonText.classList.remove('hidden');
        }
    }
</script>
@endpush
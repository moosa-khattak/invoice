<?php

namespace App\Repositories;

use App\Interfaces\StripeRepositoryInterface;
use App\Models\Invoice;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeRepository implements StripeRepositoryInterface
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Create a Stripe PaymentIntent for an invoice.
     *
     * @param Invoice $invoice
     * @return PaymentIntent
     */
    public function createPaymentIntent(Invoice $invoice)
    {
        return PaymentIntent::create([
            'amount' => (int)($invoice->balance_due * 100), // Stripe uses cents
            'currency' => strtolower($invoice->currency ?? 'USD'),
            'metadata' => ['invoice_number' => $invoice->invoice_number],
        ]);
    }

    /**
     * Retrieve a Stripe PaymentIntent by ID.
     *
     * @param string $paymentIntentId
     * @return PaymentIntent
     */
    public function retrievePaymentIntent(string $paymentIntentId)
    {
        return PaymentIntent::retrieve($paymentIntentId);
    }

    /**
     * Refund a Stripe PaymentIntent by ID.
     *
     * @param string $paymentIntentId
     * @param float|null $amount
     * @return \Stripe\Refund
     */
    public function refundPayment(string $paymentIntentId, ?float $amount = null)
    {
        $params = [
            'payment_intent' => $paymentIntentId,
        ];

        if ($amount) {
            $params['amount'] = (int)($amount * 100);
        }

        return \Stripe\Refund::create($params);
    }
}

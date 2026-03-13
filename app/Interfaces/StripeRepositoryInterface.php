<?php

namespace App\Interfaces;

use App\Models\Invoice;

interface StripeRepositoryInterface
{
    /**
     * Create a Stripe PaymentIntent for an invoice.
     *
     * @param Invoice $invoice
     * @return \Stripe\PaymentIntent
     */
    public function createPaymentIntent(Invoice $invoice);

    /**
     * Retrieve a Stripe PaymentIntent by ID.
     *
     * @param string $paymentIntentId
     * @return \Stripe\PaymentIntent
     */
    public function retrievePaymentIntent(string $paymentIntentId);

    /**
     * Refund a Stripe PaymentIntent by ID.
     *
     * @param string $paymentIntentId
     * @param float|null $amount
     * @return \Stripe\Refund
     */
    public function refundPayment(string $paymentIntentId, ?float $amount = null);
}

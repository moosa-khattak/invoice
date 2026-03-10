<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Interfaces\InvoiceRepositoryInterface;
use App\Interfaces\StripeRepositoryInterface;

class StripeController extends Controller
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository,
        protected StripeRepositoryInterface $stripeRepository,
        protected InvoiceService $service
    ) {}

    public function payment(string $id)
    {
        $invoice = $this->invoiceRepository->getByInvoiceNumber($id);

        if ($invoice->balance_due <= 0.1) {
            return redirect()->route('allinvoices')->with('success', 'This invoice is already paid.');
        }

        try {
            $paymentIntent = $this->stripeRepository->createPaymentIntent($invoice);
            $clientSecret = $paymentIntent->client_secret;

            return view('pay', compact('invoice', 'clientSecret'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error initializing payment: ' . $e->getMessage());
        }
    }

    public function processPayment(Request $request, string $id)
    {
        $invoice = $this->invoiceRepository->getByInvoiceNumber($id);

        if ($invoice->balance_due <= 0.1) {
            return redirect()->route('allinvoices')->with('error', 'Invoice was already paid.');
        }

        $paymentIntentId = $request->input('payment_intent_id');

        if (!$paymentIntentId) {
            return redirect()->route('invoice.payment', $id)->with('error', 'Payment failed. Please try again.');
        }

        try {
            $paymentIntent = $this->stripeRepository->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Update invoice status
                $amountPaid = $paymentIntent->amount / 100;

                $this->invoiceRepository->update($invoice, [
                    'status' => 'paid',
                    'amount_paid' => ($invoice->amount_paid ?? 0) + $amountPaid,
                    'balance_due' => max(0, $invoice->balance_due - $amountPaid),
                    'payment_method' => $paymentIntent->payment_method_types[0] ?? 'stripe',
                ]);

                $invoice->refresh();
                return view('successfullypaid', compact('invoice', 'amountPaid'));
            }

            return redirect()->route('invoice.payment', $id)->with('error', 'Payment was not successful. Status: ' . $paymentIntent->status);
        } catch (\Exception $e) {
            return redirect()->route('invoice.payment', $id)->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }
}

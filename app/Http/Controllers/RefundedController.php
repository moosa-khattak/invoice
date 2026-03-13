<?php

namespace App\Http\Controllers;

use App\Interfaces\InvoiceRepositoryInterface;
use App\Interfaces\StripeRepositoryInterface;
use Illuminate\Http\Request;

class RefundedController extends Controller
{
    public function __construct(
        protected InvoiceRepositoryInterface $repository,
        protected StripeRepositoryInterface $stripeRepository
    ) {}
   public function processRefund(Request $request, string $id, \App\Interfaces\StripeRepositoryInterface $stripeRepository)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        if ($invoice->status === 'Refunded') {
            return back()->with('error', 'This invoice has already been refunded.');
        }

        if (!in_array($invoice->status, ['Paid', 'Partial'])) {
            return back()->with('error', 'Only paid or partially paid invoices can be refunded.');
        }

        $refundAmount = $invoice->amount_paid;

        try {
            if ($invoice->payment_method === 'stripe' || $invoice->payment_method === 'card') {
                if ($invoice->transaction_id) {
                    $stripeRepository->refundPayment($invoice->transaction_id);
                } else {
                    return back()->with('error', 'Stripe transaction ID not found. Automated refund cannot be processed.');
                }
            }
            $this->repository->update($invoice, [
                'status' => 'Refunded',
                'amount_refunded' => ($invoice->amount_refunded ?? 0) + $refundAmount,
                'amount_paid' => 0,
                'balance_due' => $invoice->total,
            ]);

            return back()->with('success', 'Refund processed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }
}

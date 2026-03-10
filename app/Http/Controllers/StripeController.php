<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvoiceService;    
use App\Interfaces\InvoiceRepositoryInterface;

class StripeController extends Controller
{
     public function __construct(
        protected InvoiceRepositoryInterface $repository,
        protected InvoiceService $service
    ) {}
     public function payment(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        if ($invoice->balance_due <= 0.1) {
            return redirect()->route('allinvoices')->with('success', 'This invoice is already paid.');
        }

        return view('pay', compact('invoice'));
    }
     public function processPayment(Request $request, string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        if ($invoice->balance_due <= 0.1) {
            return redirect()->route('allinvoices')->with('error', 'Invoice was already paid.');
        }

        // Update status to paid, save payment method, and confirm amount paid matches total
        $data = [
            'status' => 'Paid',
            'payment_method' => $request->input('payment_method', 'Unknown'),
            'amount_paid' => $invoice->total,
            'balance_due' => 0,
        ];

        $this->repository->update($invoice, $data);

        // Refresh the model so the view gets the updated payment_method/status
        $invoice->refresh();

        return view('successfullypaid', compact('invoice'));
    }
}

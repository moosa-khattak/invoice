<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Interfaces\InvoiceRepositoryInterface;
use App\Models\Currency;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceRepositoryInterface $repository,
        protected InvoiceService $service
    ) {}

    public function index()
    {
        $invoices = $this->repository->getAll();
        return view('allinvoice', compact('invoices'));
    }

    public function create()
    {
        $currencies = Currency::all();
        $nextInvoiceNumber = $this->repository->getNextInvoiceNumber();

        return view('invoice', compact('currencies', 'nextInvoiceNumber'));
    }

    public function store(InvoiceRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to create an invoice!');
        }

        $logoPath = $this->service->processLogoUpload($request);

        $totals = $this->service->calculateTotals(
            $request->input('items', []),
            (float) $request->input('shipping', 0),
            (float) $request->input('discount_rate', 0),
            (float) $request->input('tax_rate', 0),
            (float) $request->input('amount_paid', 0)
        );

        $data = array_merge($request->validated(), [
            'logo_path' => $logoPath,
            'items' => $totals['items'],
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'tax' => $totals['tax'],
            'total' => $totals['total'],
            'balance_due' => $totals['balance_due'],
            'po_number' => $request->input('po_number'),
            'shipping' => (float) $request->input('shipping', 0),
            'discount_rate' => (float) $request->input('discount_rate', 0),
            'tax_rate' => (float) $request->input('tax_rate', 0),
            'amount_paid' => (float) $request->input('amount_paid', 0),
            'status' => $totals['status'],
            'payment_method' => $request->input('action') === 'cash' ? 'cash' : null,
        ]);
        unset($data['logo']); // remove raw file upload, already saved as logo_path

        $invoice = $this->repository->create($data);

        // Check determine the action requested
        if ($request->input('action') === 'pay') {
            return redirect()->route('invoice.payment', ['id' => $invoice->invoice_number]);
        }

        if ($request->input('action') === 'cash') {
            return redirect()->route('invoice.cash', ['id' => $invoice->invoice_number]);
        }

        return redirect()->route('invoice.show', $invoice->invoice_number)->with('success', 'Invoice saved successfully!');
    }

    public function show(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        return view('showinvoice', compact('invoice'));
    }

    public function edit(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        $currencies = Currency::all();
        return view("editinvoice", compact("invoice", "currencies"));
    }

    public function update(InvoiceRequest $request, string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        $logoPath = $invoice->logo_path;
        if ($newLogoPath = $this->service->processLogoUpload($request)) {
            $logoPath = $newLogoPath;
        }

        $totals = $this->service->calculateTotals(
            $request->input('items', []),
            (float) $request->input('shipping', 0),
            (float) $request->input('discount_rate', 0),
            (float) $request->input('tax_rate', 0),
            (float) $request->input('amount_paid', 0)
        );

        $data = array_merge($request->validated(), [
            'logo_path' => $logoPath,
            'items' => $totals['items'],
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'tax' => $totals['tax'],
            'total' => $totals['total'],
            'balance_due' => $totals['balance_due'],
            'po_number' => $request->input('po_number'),
            'shipping' => (float) $request->input('shipping', 0),
            'discount_rate' => (float) $request->input('discount_rate', 0),
            'tax_rate' => (float) $request->input('tax_rate', 0),
            'amount_paid' => (float) $request->input('amount_paid', 0),
            'status' => $totals['status'],
            'payment_method' => $request->input('action') === 'cash' ? 'cash' : $invoice->payment_method,
        ]);
        unset($data['logo']); // remove raw file upload, already saved as logo_path

        $this->repository->update($invoice, $data);

        if ($request->input('action') === 'pay') {
            return redirect()->route('invoice.payment', ['id' => $invoice->invoice_number]);
        }

        if ($request->input('action') === 'cash') {
            return redirect()->route('invoice.cash', ['id' => $invoice->invoice_number]);
        }

        return redirect()->route('invoice.show', $invoice->invoice_number)->with('success', 'Invoice updated successfully!');
    }



    public function cashPayment(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        return view('cash_payment', compact('invoice'));
    }

    public function processCashPayment(Request $request, string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        $request->validate([
            'cash_amount' => 'required|numeric|min:0.01|max:' . $invoice->balance_due,
        ]);

        $cashReceived = (float) $request->input('cash_amount');
        $newAmountPaid = ($invoice->amount_paid ?? 0) + $cashReceived;
        $newBalanceDue = max(0, $invoice->total - $newAmountPaid);

        // Determine status
        if ($newBalanceDue <= 0.1) {
            $status = 'Paid';
        } elseif ($newBalanceDue < $invoice->total) {
            $status = 'Partial';
        } else {
            $status = 'Unpaid';
        }

        $this->repository->update($invoice, [
            'amount_paid' => $newAmountPaid,
            'balance_due' => $newBalanceDue,
            'status' => $status,
            'payment_method' => 'cash',
        ]);

        return redirect()->route('invoice.show', $invoice->invoice_number)->with('success', 'Cash payment of ' . $invoice->currency . ' ' . number_format($cashReceived, 2) . ' recorded successfully!');
    }

    public function destroy(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        $this->repository->delete($invoice);

        return redirect()->route('allinvoices')->with('success', 'Invoice deleted successfully!');
    }
}

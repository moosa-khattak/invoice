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
        ]);
        unset($data['logo']); // remove raw file upload, already saved as logo_path

        $invoice = $this->repository->create($data);

        // Check determine the action requested
        if ($request->input('action') === 'pay') {
            return redirect()->route('invoice.payment', ['id' => $invoice->invoice_number]);
        }

        return redirect()->back()->with('success', 'Invoice saved successfully!');
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
        ]);
        unset($data['logo']); // remove raw file upload, already saved as logo_path

        $this->repository->update($invoice, $data);

        if ($request->input('action') === 'pay') {
            return redirect()->route('invoice.payment', ['id' => $invoice->invoice_number]);
        }

        return redirect()->route('allinvoices')->with('success', 'Invoice updated successfully!');
    }

   

    public function destroy(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        $this->repository->delete($invoice);

        return redirect()->route('allinvoices')->with('success', 'Invoice deleted successfully!');
    }

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

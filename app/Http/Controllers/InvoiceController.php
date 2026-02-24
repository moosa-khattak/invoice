<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Currency;
use App\Repositories\InvoiceRepository;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceRepository $repository,
        protected InvoiceService $service
    ) {}

    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = $this->repository->getAll();
        return view('allinvoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $currencies = Currency::all();
        $nextInvoiceNumber = $this->repository->getNextInvoiceNumber();

        return view('invoice', compact('currencies', 'nextInvoiceNumber'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(InvoiceRequest $request)
    {
        // Handle Logo Upload
        $logoPath = $this->service->processLogoUpload($request);
        
        // Calculate Totals (Subtotal, Tax, Discount, Total)
        $totals = $this->service->calculateTotals(
            $request->input('items', []),
            (float) $request->input('shipping', 0),
            (float) $request->input('discount_rate', 0),
            (float) $request->input('tax_rate', 0),
            (float) $request->input('amount_paid', 0)
        );

        // Prepare Data for Storage
        $data = array_merge($request->validated(), [
            'logo_path' => $logoPath,
            'items' => $totals['items'],
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'tax' => $totals['tax'],
            'total' => $totals['total'],
            'balance_due' => $totals['balance_due'],
            'po_number' => $request->input('po'),
            'shipping' => (float) $request->input('shipping', 0),
            'discount_rate' => (float) $request->input('discount_rate', 0),
            'tax_rate' => (float) $request->input('tax_rate', 0),
            'amount_paid' => (float) $request->input('amount_paid', 0),
        ]);

        $this->repository->create($data);

        return redirect()->back()->with('success', 'Invoice saved successfully!');
    }

    /**
     * Display the specified invoice.
     * 
     * @param string $id The invoice_number
     */
    public function show(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        return view('showinvoice', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     * 
     * @param string $id The invoice_number
     */
    public function edit(string $id){
         $invoice = $this->repository->getByInvoiceNumber($id);
         $currencies = Currency::all();
         return view("editinvoice" , compact("invoice", "currencies"));
    }

    /**
     * Update the specified invoice in storage.
     * 
     * @param InvoiceRequest $request
     * @param string $id The invoice_number
     */
    public function update(InvoiceRequest $request, string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        // Handle Logo Upload (Delete old if replaced)
        $logoPath = $invoice->logo_path;
        if ($newLogoPath = $this->service->processLogoUpload($request)) {
            $logoPath = $newLogoPath;
        }

        // Recalculate Totals
        $totals = $this->service->calculateTotals(
            $request->input('items', []),
            (float) $request->input('shipping', 0),
            (float) $request->input('discount_rate', 0),
            (float) $request->input('tax_rate', 0),
            (float) $request->input('amount_paid', 0)
        );

        // Prepare Data
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
        ]);

        $this->repository->update($invoice, $data);

        return redirect()->route('allinvoices')->with('success', 'Invoice updated successfully!');
    }

    /**
     * Download the invoice as a PDF.
     * 
     * @param string $id The invoice_number
     */
    public function downloadPdf(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Remove the specified invoice from storage.
     * 
     * @param string $id The invoice_number
     */
    public function destroy(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        $this->repository->delete($invoice);

        return redirect()->route('allinvoices')->with('success', 'Invoice deleted successfully!');
    }
}

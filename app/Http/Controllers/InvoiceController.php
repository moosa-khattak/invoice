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
        ]);

        $this->repository->create($data);

        return redirect()->back()->with('success', 'Invoice saved successfully!');
    }

    public function show(string $id)
    {
        $invoice = $this->repository->getById($id);
        return view('showinvoice', compact('invoice'));
    }
    public function edit(string $id){
         $invoice = $this->repository->getById($id);
         $currencies = Currency::all();
         return view("editinvoice" , compact("invoice", "currencies"));
    }

    public function update(InvoiceRequest $request, string $id)
    {
        $invoice = $this->repository->getById($id);

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
        ]);

        $this->repository->update($invoice, $data);

        return redirect()->route('allinvoices')->with('success', 'Invoice updated successfully!');
    }

    public function downloadPdf(string $id)
    {
        $invoice = $this->repository->getById($id);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function destroy(string $id)
    {
        $invoice = $this->repository->getById($id);
        $this->repository->delete($invoice);

        return redirect()->route('allinvoices')->with('success', 'Invoice deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Interfaces\InvoiceRepositoryInterface;
use App\Services\InvoiceService;

class PDFController extends Controller
{
     public function __construct(
        protected InvoiceRepositoryInterface $repository,
        protected InvoiceService $service
    ) {}
     public function downloadPdf(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}

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

        // DomPDF renders files better as base64 inline strings
        $logoBase64 = null;
        if ($invoice->logo_path) {
            $logoPath = storage_path('app/public/' . $invoice->logo_path);
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'logoBase64'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}

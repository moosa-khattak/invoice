<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceRepository
{
    public function getAll()
    {
        return Invoice::all();
    }

    public function getById($id)
    {
        return Invoice::findOrFail($id);
    }

    public function getByInvoiceNumber($invoice_number)
    {
        return Invoice::where('invoice_number', $invoice_number)->firstOrFail();
    }

    public function create(array $data)
    {
        return Invoice::create($data);
    }

    public function update(Invoice $invoice, array $data)
    {
        if (isset($data['logo_path']) && $invoice->logo_path && $data['logo_path'] !== $invoice->logo_path) {
            Storage::disk('public')->delete($invoice->logo_path);
        }

        $invoice->update($data);
        return $invoice;
    }

    public function delete(Invoice $invoice)
    {
        if ($invoice->logo_path) {
            Storage::disk('public')->delete($invoice->logo_path);
        }

        return $invoice->delete();
    }

    public function getNextInvoiceNumber()
    {
        $maxNumber = Invoice::max('invoice_number');
        return $maxNumber ? (int) $maxNumber + 1 : 1;
    }
}

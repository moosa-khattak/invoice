<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceRepository
{
    public function getAll()
    {
        return Invoice::orderBy('id', 'asc')->get();
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
        return $invoice->update($data);
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
        $lastInvoice = Invoice::latest()->first();
        if (!$lastInvoice) {
            return 'INV-001';
        }

        $lastNumber = intval(substr($lastInvoice->invoice_number, 4));
        return 'INV-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}

<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceRepository
{
    /**
     * Get all invoices, ordered by creation date descending.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Invoice::latest()->get();
    }

    /**
     * Get an invoice by its ID.
     * 
     * @param string $id
     * @return Invoice
     */
    public function getById($id)
    {
        return Invoice::findOrFail($id);
    }

    /**
     * Get an invoice by its Invoice Number.
     * 
     * @param string $invoice_number
     * @return Invoice
     */
    public function getByInvoiceNumber($invoice_number)
    {
        return Invoice::where('invoice_number', $invoice_number)->firstOrFail();
    }

    /**
     * Create a new invoice.
     * 
     * @param array $data
     * @return Invoice
     */
    public function create(array $data)
    {
        return Invoice::create($data);
    }

    /**
     * Update an existing invoice.
     * 
     * @param Invoice $invoice
     * @param array $data
     * @return bool
     */
    public function update(Invoice $invoice, array $data)
    {
        return $invoice->update($data);
    }

    /**
     * Delete an invoice.
     * 
     * @param Invoice $invoice
     * @return bool|null
     */
    public function delete(Invoice $invoice)
    {
        if ($invoice->logo_path) {
            Storage::disk('public')->delete($invoice->logo_path);
        }
        return $invoice->delete();
    }

    /**
     * Generate the next invoice number.
     * 
     * @return string
     */
    public function getNextInvoiceNumber()
    {
        $lastInvoice = Invoice::latest()->first();
        if (!$lastInvoice) {
            return 'INV-001';
        }

        // Extract number from 'INV-XXX'
        $lastNumber = intval(substr($lastInvoice->invoice_number, 4));
        return 'INV-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}

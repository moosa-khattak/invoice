<?php

namespace App\Interfaces;

use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByInvoiceNumber($invoice_number);
    public function create(array $data);
    public function update(Invoice $invoice, array $data);
    public function delete(Invoice $invoice);
    public function getNextInvoiceNumber();
}

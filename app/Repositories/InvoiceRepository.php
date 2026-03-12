<?php

namespace App\Repositories;

use App\Interfaces\InvoiceRepositoryInterface;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function getAll()
    {
        $user_id = Auth::user()->id;
        // print_r($user_id);

        return Invoice::with(['items', 'meta'])->where('user_id', $user_id)->orderBy('id', 'asc')->get();
    }

    public function getById($id)
    {
        $user_id = Auth::user()->id;
        return Invoice::with(['items', 'meta'])->where('user_id', $user_id)->findOrFail($id);
    }

    public function getByInvoiceNumber($invoice_number)
    {
        $user_id = Auth::user()->id;
        return Invoice::with(['items', 'meta'])->where('user_id', $user_id)->where('invoice_number', $invoice_number)->firstOrFail();
    }

    public function create(array $data)
    {
        $invoiceData = collect($data)->except(['items', 'from', 'bill_to', 'ship_to', 'po_number', 'logo_path', 'notes', 'terms'])->toArray();
        $invoiceData['user_id'] = Auth::user()->id;

        $invoice = Invoice::create($invoiceData);

        if (isset($data['items']) && is_array($data['items'])) {
            $itemsData = array_map(function ($item) {
                return [
                    'name' => $item['Item'] ?? $item['name'] ?? null,
                    'quantity' => $item['Quantity'] ?? $item['quantity'] ?? 0,
                    'rate' => $item['Rate'] ?? $item['rate'] ?? 0,
                    'amount' => $item['Amount'] ?? $item['amount'] ?? 0,
                ];
            }, $data['items']);
            $invoice->items()->createMany($itemsData);
        }

        $metaData = collect($data)->only(['from', 'bill_to', 'ship_to', 'po_number', 'logo_path', 'notes', 'terms'])->toArray();
        $invoice->meta()->create($metaData);

        return $invoice->load(['items', 'meta']);
    }

    public function update(Invoice $invoice, array $data)
    {
        $invoiceData = collect($data)->except(['items', 'from', 'bill_to', 'ship_to', 'po_number', 'logo_path', 'notes', 'terms'])->toArray();
        if (!empty($invoiceData)) {
            $invoice->update($invoiceData);
        }

        if (isset($data['items']) && is_array($data['items'])) {
            $invoice->items()->delete();
            $itemsData = array_map(function ($item) {
                return [
                    'name' => $item['Item'] ?? $item['name'] ?? null,
                    'quantity' => $item['Quantity'] ?? $item['quantity'] ?? 0,
                    'rate' => $item['Rate'] ?? $item['rate'] ?? 0,
                    'amount' => $item['Amount'] ?? $item['amount'] ?? 0,
                ];
            }, $data['items']);
            $invoice->items()->createMany($itemsData);
        }

        $metaData = collect($data)->only(['from', 'bill_to', 'ship_to', 'po_number', 'logo_path', 'notes', 'terms'])->toArray();
        if (!empty($metaData)) {
            if ($invoice->meta) {
                $invoice->meta()->update($metaData);
            } else {
                $invoice->meta()->create($metaData);
            }
        }

        return $invoice->load(['items', 'meta']);
    }

    public function delete(Invoice $invoice)
    {
        if ($invoice->meta && $invoice->meta->logo_path) {
            Storage::disk('public')->delete($invoice->meta->logo_path);
        }
        return $invoice->delete();
    }

    public function getNextInvoiceNumber()
    {
        $user_id = Auth::user()->id;
        $lastInvoice = Invoice::where('user_id', $user_id)->latest()->first();

        if (!$lastInvoice) {
            return 'INV-001';
        }

        $lastNumber = intval(substr($lastInvoice->invoice_number, 4));
        return 'INV-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}

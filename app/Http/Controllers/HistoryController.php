<?php

namespace App\Http\Controllers;

use App\Interfaces\InvoiceRepositoryInterface;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(
        protected InvoiceRepositoryInterface $repository
    ) {}
    public function history(string $id)
    {
        $invoice = $this->repository->getByInvoiceNumber($id);
        $transactions = $invoice->transactions()->orderBy('created_at', 'desc')->paginate(4);
        return view('invoice_history', compact('invoice', 'transactions'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\Currency;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('allinvoice', compact('invoices'));
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('invoice', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // // Calculate totals on backend for security/reliability
        $items = $request->input('items', []);
        $subtotal = 0;
        
        // // Ensure items is an array before processing
        if (is_array($items)) {
            // Clean up items (remove array keys if they became non-sequential)
            $items = array_values($items);
            
            foreach ($items as &$item) {
                // Assuming Quantity and Rate always exist or defaut to 0
                $qty = (int)($item['Quantity'] ?? 0);
                $rate = (int)($item['Rate'] ?? 0);
                $amount = $qty * $rate;
                $item['Amount'] = $amount; // Ensure backend calc is saved
                $subtotal += $amount;
            }
            unset($item);
        }

        // Retrieve and sanitize inputs
        // Note: inputs named discount_rate/tax_rate in UI to clearer
        $shipping = (float) $request->input('shipping', 0);
        $discountRate = (float) $request->input('discount_rate', 0);
        $taxRate = (float) $request->input('tax_rate', 0);
        $amountPaid = (float) $request->input('amount_paid', 0);

        // Calculate Monetary Values
        // Discount Amount = Subtotal * (Rate / 100)
        $discountAmount = $subtotal * ($discountRate / 100);
        
        // Tax Amount = (Subtotal - DiscountAmount) * (Rate / 100)
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($taxRate / 100);

        // Calculate Final Total
        $total = ($subtotal - $discountAmount) + $taxAmount + $shipping;
        
        // Calculate Balance Due
        $balanceDue = $total - $amountPaid;

        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'from' => $request->from,
            'bill_to' => $request->bill_to,
            'ship_to' => $request->ship_to,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'payment_terms' => $request->payment_terms,
            'po_number' => $request->po,
            'logo_path' => $logoPath,
            'header_columns' => $request->input('header_columns'),
            'items' => $items, 
            'notes' => $request->notes,
            'terms' => $request->terms,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount_rate' => $discountRate,
            'discount' => $discountAmount,
            'tax_rate' => $taxRate,
            'tax' => $taxAmount,
            'total' => $total,
            'currency' => $request->input('currency', 'USD'),
            'amount_paid' => $amountPaid,
            'balance_due' => $balanceDue,
        ]);

        return redirect()->back()->with('success', 'Invoice saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

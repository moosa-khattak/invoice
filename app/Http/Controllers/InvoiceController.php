<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\Currency;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        // Generate next invoice number
        $lastInvoice = Invoice::orderBy('invoice_number', 'desc')->first();
        $nextInvoiceNumber = $lastInvoice ? (int)$lastInvoice->invoice_number + 1 : 1;
        
        return view('invoice', compact('currencies', 'nextInvoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        } elseif ($request->filled('logo_base64')) {
            $base64Image = $request->input('logo_base64');
            // Basic check to see if it's a data URL
            if (str_contains($base64Image, ';base64,')) {
                $image_parts = explode(";base64,", $base64Image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1] ?? 'png';
                $image_data = $image_parts[1] ?? '';
                
                if (!empty($image_data)) {
                    $image_base64 = base64_decode($image_data);
                    
                    $filename = 'logos/' . uniqid() . '.' . $image_type;
                    Storage::disk('public')->put($filename, $image_base64);
                    $logoPath = $filename;
                }
            }
        }

        // Calculate totals on backend for security/reliability
        $items = $request->input('items', []);
        $subtotal = 0;
        
        // Ensure items is an array before processing
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
        $invoice = Invoice::findOrFail($id);
        return view('showinvoice', compact('invoice'));
    }

    /**
     * Download the invoice as PDF.
     */
    public function downloadPdf(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Cleanup logo file if it exists
        if ($invoice->logo_path) {
            Storage::disk('public')->delete($invoice->logo_path);
        }
        
        $invoice->destroy($id);
        return redirect()->route('allinvoices')->with('success', 'Invoice deleted successfully!');
    }
}

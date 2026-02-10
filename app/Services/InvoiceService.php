<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function processLogoUpload($request)
    {
        if ($request->hasFile('logo')) {
            return $request->file('logo')->store('logos', 'public');
        }

        if ($request->filled('logo_base64')) {
            $base64Image = $request->input('logo_base64');
            if (str_contains($base64Image, ';base64,')) {
                $image_parts = explode(";base64,", $base64Image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1] ?? 'png';
                $image_data = base64_decode($image_parts[1] ?? '');

                if (!empty($image_data)) {
                    $filename = 'logos/' . uniqid() . '.' . $image_type;
                    Storage::disk('public')->put($filename, $image_data);
                    return $filename;
                }
            }
        }

        return null;
    }

    public function calculateTotals(array $items, float $shipping, float $discountRate, float $taxRate, float $amountPaid)
    {
        $subtotal = 0;
        
        // Clean up items array (ensure sequential keys)
        $items = array_values($items);

        foreach ($items as &$item) {
            $qty = (float)($item['Quantity'] ?? 0);
            $rate = (float)($item['Rate'] ?? 0);
            $item['Amount'] = $qty * $rate;
            $subtotal += $item['Amount'];
        }
        unset($item); // Unset reference to avoid unexpected behavior

        $discountAmount = $subtotal * ($discountRate / 100);
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($taxRate / 100);
        $total = ($subtotal - $discountAmount) + $taxAmount + $shipping;
        $balanceDue = $total - $amountPaid;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'tax' => $taxAmount,
            'total' => $total,
            'balance_due' => $balanceDue
        ];
    }
}

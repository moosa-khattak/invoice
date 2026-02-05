<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_number' => 'required|string',
            'from' => 'required|string',
            'bill_to' => 'required|string',
            'ship_to' => 'required|string',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'payment_terms' => 'required|string',
            'po' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'items' => 'required|array',
            'header_columns' => 'nullable|array',
            'notes' => 'required|string',
            'terms' => 'required|string',
            'subtotal' => 'required|numeric',
            'shipping' => 'nullable|numeric|min:0',
            'discount_rate' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'required|numeric',
            'currency' => 'required|string',
            'amount_paid' => 'nullable|numeric',
            'balance_due' => 'required|numeric',
        ];
    }
}

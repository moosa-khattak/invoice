<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
        $id = $this->route('id');
        return [
            'invoice_number' => [
                'required',
                'string',
                Rule::unique('invoices', 'invoice_number')
                    ->where('user_id', Auth::id())
                    ->ignore($id, 'invoice_number'),
            ],
            'from' => 'required|string',
            'bill_to' => 'required|string',
            'ship_to' => 'nullable|string',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'po_number' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.Item' => 'required|string',
            'items.*.Quantity' => 'required|numeric|min:0.01',
            'items.*.Rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
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

    /**
     * Get custom error messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "invoice_number.required" => "Invoice Number is Required",
            "from.required" => "From field is required",
            "bill_to.required" => "Bill To is required",
            "date.required" => "Date is Required",
            "due_date.required" => "Due Date is Required",
            "po_number.required" => "PO Number is Required",
            "items.required" => "At least one item is required",
            "items.*.Item.required" => "Item name is required",
            "items.*.Quantity.required" => "Quantity is required",
            "items.*.Quantity.min" => "Quantity must be at least 0.01",
            "items.*.Rate.required" => "Rate is required",
        ];
    }
}

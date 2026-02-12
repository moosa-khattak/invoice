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
        $id = $this->route('id');
        return [
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $id,
            'from' => 'required|string',
            'bill_to' => 'required|string',
            'ship_to' => 'nullable|string',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'payment_terms' => 'required|string',
            'po_number' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'items' => 'required|array',
            'header_columns' => 'nullable|array',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            // 'subtotal' => 'required|numeric',
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

    public function messages(){
            return [
              
           "invoice_number.required" => "Invoice Number is Required" ,
           "from.required" => " from field is required",
           "bill_to.required" => "Bill_To is required",
           "date.required" => "Date is Required",
           "due_date.required" => "Due Date is Required",
           "payment_terms.required" => "payment_terms is Required",
           "items.required" => "items is Required",
        
            ];
    } 
}

<div class="max-w-[910px] mx-4 md:px-6 p-6 bg-white rounded-lg">
    <!-- Items Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="invoice-table">
            <thead>
                <tr
                    class="bg-slate-900 text-white text-sm font-semibold"
                    id="header-row">
                    <th class="p-3 whitespace-nowrap">Item</th>
                    <th class="p-3 text-center whitespace-nowrap">Quantity</th>
                    <th class="p-3 text-center whitespace-nowrap">Rate</th>
                    <th class="p-3 text-right whitespace-nowrap">Amount</th>
                    <th class="p-3 w-10"></th>
                    <!-- Delete Action Column -->
                </tr>
            </thead>
            <tbody id="items-body">
                <!-- Rows will be added here dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap gap-4 mt-4">
        <button
            type="button"
            onclick="addRow()"
            class="flex-1 sm:flex-none text-green-600 border border-green-500 px-4 py-2 rounded-md font-medium hover:bg-green-50 transition">
            + Line Item
        </button>
    </div>

    <!-- Notes & Summary -->
    <div class="grid grid-cols-12 gap- mt-10">
        <!-- Left -->
        <div class="col-span-12 md:col-span-8">
            <x-textarea
                name="notes"
                label="Notes"
                placeholder="Notes - any relevant information not already covered"
                :value="$invoice->notes ?? ''"
                class="w-[70%]" />

            <div class="mt-6">
                <x-textarea
                    name="terms"
                    label="Terms"
                    placeholder="Terms and conditions - late fees, payment methods, delivery schedule"
                    :value="$invoice->terms ?? ''"
                    class="w-[70%]" />
            </div>
        </div>

        <!-- Right -->
        <div class="col-span-12 md:col-span-4 space-y-4 text-sm">
            <!-- Hidden inputs for calculated values required by backend -->
            <input
                type="hidden"
                value="{{ old('subtotal', $invoice->subtotal ?? '') }}"
                name="subtotal"
                id="input-subtotal" />
            <input
                type="hidden"
                value="{{ old('total', $invoice->total ?? '') }}"
                name="total"
                id="input-total" />
            <input
                type="hidden"
                value="{{ old('balance_due', $invoice->balance_due ?? '') }}"
                name="balance_due"
                id="input-balance-due" />
            <input
                type="hidden"
                name="currency_hidden"
                value="{{ old('currency', $invoice->currency ?? 'USD') }}"
                id="input-currency" />

            <div class="flex justify-between">
                <span>Subtotal</span>

                <div class="flex gap-5">
                    <span class="currency-code-display">USD</span>
                    <span id="subtotal">
                        {{ number_format(old('subtotal', $invoice->subtotal ?? 0), 0) }}
                    </span>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Discount</span>
                <div class="flex items-center gap-2">
                    <span class="">%</span>
                    <x-input
                        name="discount_rate"
                        type="number"
                        :value="$invoice->discount_rate ?? ''"
                        id="discount"
                        class="w-24 text-right"
                        min="0"
                        step="1"
                        noWrapper="true"
                        oninput="if (this.value < 0) this.value = 0;" />
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Shipping</span>

                <div class="flex gap-2 items-center">
                    <span class="currency-code-display">USD</span>

                    <x-input
                        name="shipping"
                        type="number"
                        :value="$invoice->shipping ?? ''"
                        id="shipping"
                        class="w-24 text-right"
                        min="0"
                        noWrapper="true"
                        oninput="if (this.value < 0) this.value = 0;" />
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Tax</span>
                <div class="flex items-center gap-2">
                    <span class="text-gray-500">%</span>
                    <x-input
                        name="tax_rate"
                        type="number"
                        :value="$invoice->tax_rate ?? ''"
                        id="tax"
                        class="w-24 text-right"
                        min="0"
                        noWrapper="true"
                        oninput="if (this.value < 0) this.value = 0;" />
                </div>
            </div>

            <hr class="border-gray-200" />

            <div class="flex justify-between font-semibold text-lg">
                <span>Total</span>
                <div class="flex gap-5">
                    <span id="currency" class="currency-code-display">USD</span>
                    <span id="total">
                        {{ number_format(old('total', $invoice->total ?? 0), 0) }}
                    </span>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Amount Paid</span>

                <div class="flex gap-2 items-center">
                    <span id="currency" class="currency-code-display">USD</span>
                    <x-input
                        name="amount_paid"
                        type="number"
                        :value="$invoice->amount_paid ?? ''"
                        id="paid"
                        class="w-24 text-right"
                        min="0"
                        noWrapper="true"
                        oninput="if (this.value < 0) this.value = 0;" />
                </div>
            </div>

            <div class="flex justify-between font-semibold">
                <span>Balance Due</span>
                <div class="flex gap-5">
                    <span id="currency" class="currency-code-display">USD</span>
                    <span id="balance">
                        {{ number_format(old('balance_due', $invoice->balance_due ?? 0), 0) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/table.js') }}"></script>
@endsection
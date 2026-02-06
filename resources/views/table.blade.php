<div class="max-w-[910px] mx-4 md:px-6 p-6 bg-white rounded-lg">
    <!-- Items Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="invoice-table">
            <thead>
                <tr
                    class="bg-slate-900 text-white text-sm font-semibold"
                    id="header-row"
                >
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
            class="flex-1 sm:flex-none text-green-600 border border-green-500 px-4 py-2 rounded-md font-medium hover:bg-green-50 transition"
        >
            + Line Item
        </button>
        <!-- <button
            type="button"
            onclick="addColumn()"
            class="flex-1 sm:flex-none text-blue-600 border border-blue-500 px-4 py-2 rounded-md font-medium hover:bg-blue-50 transition"
        >
            + Add Column
        </button> -->
    </div>

    <!-- Notes & Summary -->
    <div class="grid grid-cols-12 gap- mt-10">
        <!-- Left -->
        <div class="col-span-12 md:col-span-8">
            <label class="block text-gray-600 mb-2">Notes</label>
            <textarea
                class="w-[70%] border rounded-md p-3"
                name="notes"
                placeholder="Notes - any relevant information not already covered"
            >
{{ old('notes') }}</textarea
            >

            <label class="block text-gray-600 mt-6 mb-2">Terms</label>
            <textarea
                class="w-[70%] border rounded-md p-3"
                name="terms"
                placeholder="Terms and conditions - late fees, payment methods, delivery schedule"
            >
{{ old('terms') }}</textarea
            >
        </div>

        <!-- Right -->
        <div class="col-span-12 md:col-span-4 space-y-4 text-sm">
            <!-- Hidden inputs for calculated values required by backend -->
            <input
                type="hidden"
                value="{{ old('subtotal') }}"
                name="subtotal"
                id="input-subtotal"
            />
            <input
                type="hidden"
                value="{{ old('total') }}"
                name="total"
                id="input-total"
            />
            <input
                type="hidden"
                value="{{ old('balance_due') }}"
                name="balance_due"
                id="input-balance-due"
            />
            <input
                type="hidden"
                name="currency_hidden"
                value="{{ old('currency', 'USD') }}"
                id="input-currency"
            />

            <div class="flex justify-between">
                <span>Subtotal</span>

                <div class="flex gap-5">
                    <span class="currency-code-display">USD</span>
                    <span id="subtotal">
                        {{ number_format(old('subtotal', 0), 2) }}
                    </span>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Discount</span>
                <div class="flex items-center gap-2">
                    <span class="">%</span>
                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        name="discount_rate"
                        value="{{ old('discount_rate') }}"
                        id="discount"
                        class="input w-24 border rounded-md text-right px-2 py-1 focus:ring-teal-500 focus:border-teal-500"
                        oninput="if (this.value < 0) this.value = 0;"
                    />
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Shipping</span>

                <div class="flex gap-2 items-center">
                    <span class="currency-code-display">USD</span>
                    <!-- <span id="subtotal">  0.00</span> -->
                    <input
                        type="number"
                        min="0"
                        name="shipping"
                        value="{{ old('shipping') }}"
                        id="shipping"
                        class="input w-24 border rounded-md text-right px-2 py-1 focus:ring-teal-500 focus:border-teal-500"
                        oninput="if (this.value < 0) this.value = 0;"
                    />
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Tax</span>
                <div class="flex items-center gap-2">
                    <span class="text-gray-500">%</span>
                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('tax_rate') }}"
                        name="tax_rate"
                        id="tax"
                        class="input w-24 border rounded-md text-right px-2 py-1 focus:ring-teal-500 focus:border-teal-500"
                        oninput="if (this.value < 0) this.value = 0;"
                    />
                </div>
            </div>

            <hr class="border-gray-200" />

            <div class="flex justify-between font-semibold text-lg">
                <span>Total</span>
                <div class="flex gap-5">
                    <span id="currency" class="currency-code-display">USD</span>
                    <span id="total">
                        {{ number_format(old('total', 0), 2) }}
                    </span>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span>Amount Paid</span>

                <div class="flex gap-2 items-center">
                    <span id="currency" class="currency-code-display">USD</span>
                    <input
                        type="number"
                        min="0"
                        name="amount_paid"
                        value="{{ old('amount_paid') }}"
                        id="paid"
                        class="input w-24 border rounded-md text-right px-2 py-1 focus:ring-teal-500 focus:border-teal-500"
                        oninput="if (this.value < 0) this.value = 0;"
                    />
                </div>
            </div>

            <div class="flex justify-between font-semibold">
                <span>Balance Due</span>
                <div class="flex gap-5">
                    <span id="currency" class="currency-code-display">USD</span>
                    <span id="balance">
                        {{ number_format(old('balance_due', 0), 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.invoiceOldItems = @json(old('items'));
</script>

<script src="{{ asset('js/table.js') }}"></script>

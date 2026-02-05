<!-- Invoice Paper (Left Side) -->
<div
    class="flex-1 bg-white p-8 md:p-12 rounded-lg shadow-sm border border-gray-200 w-full"
>
    <!-- Top Section: Logo & Header -->
    <div
        class="flex flex-col md:flex-row justify-between items-start gap-8 mb-10"
    >
        <!-- Logo Placeholder -->
        <div class="w-full md:w-64">
            <label
                class="border border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 transition rounded-lg h-40 flex flex-col items-center justify-center cursor-pointer group"
            >
                <span
                    class="text-3xl text-gray-400 mb-2 group-hover:text-gray-500"
                >
                    +
                </span>
                <span
                    class="text-gray-400 font-medium group-hover:text-gray-500"
                >
                    Add Your Logo
                </span>
                <img
                    id="logo-preview"
                    class="hidden h-full w-full object-contain rounded-lg"
                />
                <input
                    type="file"
                    name="logo"
                    accept="image/*"
                    id="logo-input"
                    class="hidden"
                />
            </label>
        </div>

        <!-- Invoice Header -->
        <div class="w-full md:w-64 text-right">
            <h1
                class="text-5xl  text-gray-800 tracking-wide mb-4 font-bold"
            >
                INVOICE
            </h1>
            <div class="relative">
                <span class="absolute left-18 top-2.5 text-gray-400 font-bold">
                    #
                </span>
                <input
                    type="text"
                    name="invoice_number"
                    value="{{ old('invoice_number') }}"
                    class="border border-gray-200 rounded-lg py-2 pr-3 text-right focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>
        </div>
    </div>

    <!-- Middle Section: From & Meta Data -->
    <div class="flex flex-col md:flex-row gap-12">
        <!-- Left Column (From, Bill To, Ship To) -->
        <div class="flex-1 space-y-8">
            <!-- From -->
            <div>
                <input
                    type="text"
                    name="from"
                    value="{{ old('from') }}"
                    placeholder="Who is this from?"
                    class="w-full border border-gray-200 rounded-lg p-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- Bill To & Ship To Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-500 mb-2 font-medium">
                        Bill To
                    </label>
                    <input
                        type="text"
                        name="bill_to"
                        value="{{ old('bill_to') }}"
                        placeholder="Who is this to?"
                        class="w-full border border-gray-200 rounded-lg p-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                    />
                </div>
                <div>
                    <label class="block text-gray-500 mb-2 font-medium">
                        Ship To
                    </label>
                    <input
                        type="text"
                        name="ship_to"
                        value="{{ old('ship_to') }}"
                        placeholder="(optional)"
                        class="w-full border border-gray-200 rounded-lg p-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                    />
                </div>
            </div>
        </div>

        <!-- Right Column (Dates & Meta) -->
        <div class="w-full md:w-80 space-y-4">
            <!-- Date -->
            <div class="flex items-center gap-1">
                <label class="w-32 text-right text-gray-500">Date</label>
                <input
                    type="date"
                    name="invoice_date"
                    value="{{ old('invoice_date') }}"
                    class="flex-1 border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- Payment Terms -->
            <div class="flex items-center gap-1">
                <label class="w-32 text-right text-gray-500">
                    Payment Terms
                </label>
                <input
                    type="text"
                    name="payment_terms"
                    value="{{ old('payment_terms') }}"
                    class="flex-1 border border-gray-200 rounded-lg py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- Due Date -->
            <div class="flex items-center gap-1">
                <label class="w-32 text-right text-gray-500">Due Date</label>
                <input
                    type="date"
                    name="due_date"
                    value="{{ old('due_date') }}"
                    class="flex-1 border border-gray-200 rounded-lg py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>

            <!-- PO Number -->
            <div class="flex items-center gap-1">
                <label class="w-32 text-right text-gray-500">PO Number</label>
                <input
                    type="text"
                    name="po_number"
                    value="{{ old('po_number') }}"
                    class="flex-1 border border-gray-200 rounded-lg py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                />
            </div>
        </div>
    </div>

    @section('script')
            <script>
                // --- State & Config ---
                let rowCount = 0;
                let extraColumns = [];

                // --- DOM Elements ---
                const itemsBody = document.getElementById('items-body');
                const subtotalEl = document.getElementById('subtotal');
                const totalEl = document.getElementById('total');
                const balanceEl = document.getElementById('balance');
                const inputs = {
                    subtotal: document.getElementById('input-subtotal'),
                    total: document.getElementById('input-total'),
                    balance: document.getElementById('input-balance-due'),
                    shipping: document.getElementById('shipping'),
                    discount: document.getElementById('discount'),
                    tax: document.getElementById('tax'),
                    paid: document.getElementById('paid'),
                };

                // --- Event Listeners ---
                // Attach listeners to static inputs
                ['shipping', 'discount', 'tax', 'paid'].forEach((id) => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('input', calculateTotals);
                });

                // --- Functions ---

                function formatMoney(amount) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(amount);
                }

                function addRow() {
                    rowCount++;
                    const row = document.createElement('tr');
                    row.className = 'border-b border-gray-100 group';
                    // Use class for currency display
                    const selectedCurrency =
                        document.getElementById('currency-selector').value;

                    let rowHtml = `
            <td class="p-3">
                <input type="text" name="items[${rowCount}][Item]" placeholder="Description"  class="w-full bg-transparent focus:outline-none placeholder-gray-400 font-medium" />
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowCount}][Quantity]" min="0" step="1" class="w-full text-center bg-transparent focus:outline-none quantity-input" oninput="this.value = Math.max(0, Math.floor(this.value)); calculateRow(this)" />
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowCount}][Rate]" min="0" step="1" class="w-full text-center bg-transparent focus:outline-none rate-input" oninput="this.value = Math.max(0, Math.floor(this.value)); calculateRow(this)" />
            </td>
        `;

                    // Add dynamic columns
                    extraColumns.forEach((colName) => {
                        rowHtml += `
                <td class="p-3">
                    <input type="text" name="items[${rowCount}][${colName}]" class="w-full text-center bg-transparent focus:outline-none" />
                </td>
            `;
                    });

                    rowHtml += `
            <td class="p-3 text-right font-medium text-gray-700 amount-display">
                <span class="mr-2 currency-code-display">${selectedCurrency}</span>
                <span class="row-amount">0.00</span>
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="deleteRow(this)" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition">
                    &times;
                </button>
            </td>
        `;
                    row.innerHTML = rowHtml;
                    itemsBody.appendChild(row);
                    calculateTotals();
                }

                function addColumn() {
                    const colName = prompt('Enter column name:');
                    if (!colName || colName.trim() === '') return;

                    const name = colName.trim();
                    if (extraColumns.includes(name)) {
                        alert('Column already exists');
                        return;
                    }

                    extraColumns.push(name);

                    // Update Header
                    const headerRow = document.getElementById('header-row');
                    const lastHeader =
                        headerRow.children[headerRow.children.length - 2];
                    const newTh = document.createElement('th');
                    newTh.className = 'p-3 text-center';
                    newTh.textContent = name;
                    headerRow.insertBefore(newTh, lastHeader.nextSibling);

                    // Update existing rows
                    const rows = document.querySelectorAll('#items-body tr');
                    rows.forEach((row) => {
                        const lastTd = row.children[row.children.length - 2];
                        const newTd = document.createElement('td');
                        newTd.className = 'p-3';

                        const firstInput = row.querySelector('input');
                        if (firstInput) {
                            const match =
                                firstInput.name.match(/items\[(\d+)\]/);
                            if (match) {
                                const rowIndex = match[1];
                                newTd.innerHTML = `<input type="text" name="items[${rowIndex}][${name}]" class="w-full text-center bg-transparent focus:outline-none" />`;
                            }
                        }
                        row.insertBefore(newTd, lastTd.nextSibling);
                    });

                    // Update hidden inputs for header columns
                    updateHeaderColumnsInput();
                }

                function updateHeaderColumnsInput() {
                    // Remove existing header columns inputs
                    document
                        .querySelectorAll('.header-column-input')
                        .forEach((el) => el.remove());

                    // Add new hidden inputs for each column
                    extraColumns.forEach((col) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'header_columns[]';
                        input.value = col;
                        input.className = 'header-column-input';
                        document.querySelector('form').appendChild(input);
                    });
                }

                function deleteRow(btn) {
                    btn.closest('tr').remove();
                    calculateTotals();
                }

                function calculateRow(input) {
                    const row = input.closest('tr');
                    // Force integer parsing
                    const qty = Math.floor(
                        parseFloat(
                            row.querySelector('.quantity-input').value,
                        ) || 0,
                    );
                    const rate = Math.floor(
                        parseFloat(row.querySelector('.rate-input').value) || 0,
                    );
                    const amount = qty * rate;

                    row.querySelector('.row-amount').textContent =
                        formatMoney(amount);
                    calculateTotals();
                }

                // --- Currency Logic ---
                function updateCurrency() {
                    const selector =
                        document.getElementById('currency-selector');
                    const selectedCode = selector.value;

                    document
                        .querySelectorAll('.currency-code-display')
                        .forEach((el) => {
                            el.textContent = selectedCode;
                        });
                }

                document
                    .getElementById('currency-selector')
                    .addEventListener('change', updateCurrency);

                function calculateTotals() {
                    let subtotal = 0;

                    // Sum all rows
                    document
                        .querySelectorAll('#items-body tr')
                        .forEach((row) => {
                            const qty = Math.floor(
                                parseFloat(
                                    row.querySelector('.quantity-input').value,
                                ) || 0,
                            );
                            const rate = Math.floor(
                                parseFloat(
                                    row.querySelector('.rate-input').value,
                                ) || 0,
                            );
                            subtotal += qty * rate;
                        });

                    // Get additional costs/discounts
                    const shipping = parseFloat(inputs.shipping.value) || 0;
                    const discountRate = parseFloat(inputs.discount.value) || 0;
                    const taxRate = parseFloat(inputs.tax.value) || 0;
                    const paid = parseFloat(inputs.paid.value) || 0;

                    // Calculate Monetary Values
                    const discountAmount = subtotal * (discountRate / 100);
                    const taxableAmount = subtotal - discountAmount;
                    const taxAmount = taxableAmount * (taxRate / 100);

                    // Calculate final figures
                    // Formula: (Subtotal - DiscountAmount) + TaxAmount + Shipping
                    const total =
                        subtotal - discountAmount + taxAmount + shipping;
                    const balance = total - paid;

                    // Update UI Text
                    subtotalEl.textContent = formatMoney(subtotal);
                    totalEl.textContent = formatMoney(total);
                    balanceEl.textContent = formatMoney(balance);

                    // Update Hidden Inputs
                    inputs.subtotal.value = subtotal.toFixed(2);
                    inputs.total.value = total.toFixed(2);
                    inputs.balance.value = balance.toFixed(2);
                }

                // Initialize
                // Add default row if empty
                if (itemsBody.children.length === 0) {
                    addRow();
                }
            </script>
    @endsection
</div>

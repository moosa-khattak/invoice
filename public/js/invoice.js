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
function addRow(data = {}) {
    rowCount++;
    const row = document.createElement('tr');
    row.className = 'border-b border-gray-100 group';
    const selectedCurrency = document.getElementById('currency-selector').value;

    // Calculate initial amount for old data
    const initialQty = parseFloat(data.Quantity) || 0;
    const initialRate = parseFloat(data.Rate) || 0;
    const initialAmount = initialQty * initialRate;

    let rowHtml = `
            <td class="p-3">
                <input type="text" name="items[${rowCount}][Item]" value="${data.Item || ''}" placeholder="Products" class="w-full bg-transparent border border-gray-300 px-2 py-1 rounded-md focus:outline-none" />
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowCount}][Quantity]" value="${data.Quantity || ''}" min="0" class="quantity-input w-30 border border-gray-300 px-2 py-1 rounded-md" oninput="calculateRow(this)" />
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowCount}][Rate]" value="${data.Rate || ''}" min="0" class="rate-input w-30 border border-gray-300 px-2 py-1 rounded-md" oninput="calculateRow(this)" />
            </td>
        `;

    // ... (rest of your dynamic columns logic)

    rowHtml += `
            <td class="p-3 text-right font-medium text-gray-700 amount-display">
                <span class="mr-2 currency-code-display">${selectedCurrency}</span>
                <span class="row-amount border border-gray-300 px-2 py-1 rounded-md">${formatMoney(initialAmount)}</span>
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="deleteRow(this)" class="text-red-400">&times;</button>
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
    const lastHeader = headerRow.children[headerRow.children.length - 2];
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
            const match = firstInput.name.match(/items\[(\d+)\]/);
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
        parseFloat(row.querySelector('.quantity-input').value) || 0,
    );
    const rate = Math.floor(
        parseFloat(row.querySelector('.rate-input').value) || 0,
    );
    const amount = qty * rate;

    row.querySelector('.row-amount').textContent = formatMoney(amount);
    calculateTotals();
}

// --- Currency Logic ---
function updateCurrency() {
    const selector = document.getElementById('currency-selector');
    const selectedCode = selector.value;

    document.querySelectorAll('.currency-code-display').forEach((el) => {
        el.textContent = selectedCode;
    });

    // Update hidden currency input
    const currencyInput = document.getElementById('input-currency');
    if (currencyInput) currencyInput.value = selectedCode;
}

document
    .getElementById('currency-selector')
    .addEventListener('change', updateCurrency);

function calculateTotals() {
    let subtotal = 0;

    // Sum all rows
    document.querySelectorAll('#items-body tr').forEach((row) => {
        const qty = Math.floor(
            parseFloat(row.querySelector('.quantity-input').value) || 0,
        );
        const rate = Math.floor(
            parseFloat(row.querySelector('.rate-input').value) || 0,
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
    const total = subtotal - discountAmount + taxAmount + shipping;
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

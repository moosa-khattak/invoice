let rowCount = 0;

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

// Logo Preview Logic
const logoInput = document.getElementById('logo-input');
const logoPreview = document.getElementById('logo-preview');
const logoPlaceholder = document.getElementById('logo-placeholder');
const removeLogoBtn = document.getElementById('remove-logo');
const logoBase64Input = document.getElementById('logo-base64-input');

if (logoInput && logoPreview) {
    logoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const base64Data = e.target.result;
                logoPreview.src = base64Data;
                if (logoBase64Input) logoBase64Input.value = base64Data;

                logoPreview.classList.remove('hidden');
                logoPlaceholder?.classList.add('hidden');
                removeLogoBtn?.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeLogoBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        logoInput.value = '';
        logoPreview.src = '';
        if (logoBase64Input) logoBase64Input.value = '';

        logoPreview.classList.add('hidden');
        logoPlaceholder?.classList.remove('hidden');
        removeLogoBtn.classList.add('hidden');
    });
}

// --- Functions ---

function formatMoney(amount) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
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
                <input type="number" step="1" name="items[${rowCount}][Quantity]" value="${data.Quantity || ''}" min="0" class="quantity-input w-30 border border-gray-300 px-2 py-1 rounded-md" oninput="calculateRow(this)" />
            </td>
            <td class="p-3">
                <input type="number" step="1" name="items[${rowCount}][Rate]" value="${data.Rate || ''}" min="0" class="rate-input w-30 border border-gray-300 px-2 py-1 rounded-md" oninput="calculateRow(this)" />
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

function deleteRow(btn) {
    btn.closest('tr').remove();
    calculateTotals();
}

function calculateRow(input) {
    const row = input.closest('tr');
    // Allow decimals by parsing floats appropriately
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
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
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
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

    // Calculate final figures (rounded)
    // Formula: (Subtotal - DiscountAmount) + TaxAmount + Shipping
    const total = Math.round(subtotal - discountAmount + taxAmount + shipping);
    const balance = Math.round(total - paid);

    // Update UI Text
    subtotalEl.textContent = formatMoney(Math.round(subtotal));
    totalEl.textContent = formatMoney(total);
    balanceEl.textContent = formatMoney(balance);

    // Update Hidden Inputs
    inputs.subtotal.value = Math.round(subtotal);
    inputs.total.value = total;
    inputs.balance.value = balance;
}

// Initialize
// Add default row if empty
if (itemsBody.children.length === 0) {
    addRow();
}

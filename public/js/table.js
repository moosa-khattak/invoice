

document.addEventListener('DOMContentLoaded', function () {
    // 1. Get old items from Laravel session
    const oldItems = window.invoiceOldItems;

    // 2. If there are old items, populate the table with them
    if (oldItems && Object.keys(oldItems).length > 0) {
        // Clear the initial empty row if your JS adds one automatically
        document.getElementById('items-body').innerHTML = '';

        // Loop through each item and call your existing addRow function
        Object.values(oldItems).forEach((item) => {
            addRow(item);
        });
    } else {
        // 3. Fallback: If no old data and table is empty, add one blank row
        if (document.getElementById('items-body').children.length === 0) {
            addRow();
        }
    }
});

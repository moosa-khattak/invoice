 document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const tableBody = document.querySelector('tbody');
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    // Skip the empty state row
                    if (row.cells.length === 1 && row.cells[0].getAttribute('colspan')) return;

                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    });
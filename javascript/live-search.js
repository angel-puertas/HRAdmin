document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchInput').addEventListener('input', function () {
        const lowercaseInput = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let matchFound = false;

            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(lowercaseInput)) {
                    matchFound = true;
                }
            });

            if (matchFound) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
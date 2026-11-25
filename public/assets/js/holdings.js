(() => {
    const form = document.getElementById('holding-form');
    const success = document.getElementById('holding-success');
    const portfolio = form || success;
    if (!portfolio) return;

    const saveKey = 'feexray-holding-save-' + portfolio.dataset.portfolioId;

    if (form) {
        const fund = document.getElementById('fund_id');
        const customFields = document.getElementById('custom-fields');

        function showCustomFields() {
            const isCustom = fund.value === 'custom';
            customFields.hidden = !isCustom;
            // Only send custom fields when a custom fund is selected.
            customFields.querySelectorAll('input').forEach((input) => {
                input.disabled = !isCustom;
            });
        }

        fund.addEventListener('change', showCustomFields);
        showCustomFields();

        try {
            // A validation error brings the user back here.
            sessionStorage.removeItem(saveKey);
        } catch {}

        form.addEventListener('submit', () => {
            try {
                sessionStorage.setItem(saveKey, 'pending');
            } catch {}
        });
    }

    if (success) {
        try {
            const pendingSave = sessionStorage.getItem(saveKey) === 'pending';
            sessionStorage.removeItem(saveKey);

            // Show the notice only after the existing save route redirects here.
            const navigation = performance.getEntriesByType('navigation')[0];
            if (pendingSave && navigation?.type === 'navigate' && navigation.redirectCount > 0) {
                success.hidden = false;
            }
        } catch {}
    }
})();

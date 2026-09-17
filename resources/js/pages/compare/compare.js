/* Müqayisə səhifəsi — elementlərin silinməsi və siyahının təmizlənməsi (compare.blade.php-dən çıxarılıb) */
(function () {
'use strict';

const R = window.KibrisKareRoutes || {};
window.removeCompareItem = function (itemId) {
    const csrf = window.KibrisKare?.csrfToken() || '';
    fetch(R.comparesToggle || '/api/compares/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: itemId, car_id: itemId, property_id: itemId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
};

document.getElementById('clearAllCompareBtn')?.addEventListener('click', function () {
    const btn = document.getElementById('clearAllCompareBtn');
    const confirmMsg = btn?.getAttribute('data-confirm') || 'Bütün müqayisə siyahısını təmizləmək istədiyinizdən əminsiniz?';
    if (confirm(confirmMsg)) {
        const csrf = window.KibrisKare?.csrfToken() || '';
        fetch(R.comparesClear || '/api/compares/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
});
})();

function applyCheckAlls() {
    var checkAlls = document.querySelectorAll('.check-all');
    checkAlls.forEach((checkAll) => {
        checkAll.addEventListener('click', (e) => {
            checkAll.closest('table').querySelectorAll('tbody input[type="checkbox"]').forEach((checkbox) => {
                checkbox.checked = e.target.checked;
            });
        })
    });
}

document.addEventListener('DOMContentLoaded', applyCheckAlls);
document.addEventListener('turbo:load', applyCheckAlls);

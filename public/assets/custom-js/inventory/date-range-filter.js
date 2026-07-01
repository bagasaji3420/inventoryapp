document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-quick-filter]").forEach((button) => {
        button.addEventListener("click", function () {
            const modal = button.closest(".modal-content");
            const awalInput = modal.querySelector('input[name="tanggal_awal"]');
            const akhirInput = modal.querySelector('input[name="tanggal_akhir"]');

            const today = new Date();
            let awal;

            if (button.dataset.quickFilter === "week") {
                const day = today.getDay() === 0 ? 7 : today.getDay();
                awal = new Date(today);
                awal.setDate(today.getDate() - (day - 1));
            } else if (button.dataset.quickFilter === "month") {
                awal = new Date(today.getFullYear(), today.getMonth(), 1);
            }

            const toInputValue = (date) => date.toISOString().slice(0, 10);

            awalInput.value = toInputValue(awal);
            akhirInput.value = toInputValue(today);
        });
    });
});

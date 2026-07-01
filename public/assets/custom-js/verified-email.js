document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("resendBtn");

    if (!btn) return;

    const cooldown = 60;
    const storageKey = "resendCooldown";

    let lastClick = localStorage.getItem(storageKey);

    if (lastClick) {
        const diff = Math.floor((Date.now() - lastClick) / 1000);

        if (diff < cooldown) {
            startCooldown(cooldown - diff);
        }
    }

    btn.closest("form").addEventListener("submit", function () {
        localStorage.setItem(storageKey, Date.now());
    });

    function startCooldown(seconds) {
        btn.disabled = true;

        const interval = setInterval(() => {
            btn.innerText = `Wait ${seconds}s`;
            seconds--;

            if (seconds < 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.innerText = "Resend";
                localStorage.removeItem(storageKey);
            }
        }, 1000);
    }
});
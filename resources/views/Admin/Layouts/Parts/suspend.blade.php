@if ($suspended_until && now()->lt($suspended_until))
    <div class="alert alert-warning text-center" id="suspendAlert">
        Your account is temporarily suspended.

        <br>
        Remaining time: <strong id="countdown"></strong>
    </div>

    <script>
        const endTime = new Date("{{ $suspended_until }}").getTime();
        const countdownEl = document.getElementById("countdown");
        const alertEl = document.getElementById("suspendAlert");

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                countdownEl.innerHTML = "Expired";

                // 🔥 hide alert
                if (alertEl) {
                    alertEl.style.display = "none";
                }

                // optional reload (biar middleware ke-trigger lagi)
                setTimeout(() => {
                    location.reload();
                }, 1500);

                return;
            }

            const minutes = Math.floor((distance / (1000 * 60)) % 60);
            const seconds = Math.floor((distance / 1000) % 60);

            countdownEl.innerHTML = `${minutes}m ${seconds}s`;
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
@endif

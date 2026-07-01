document.addEventListener("DOMContentLoaded", function () {
    if (typeof Echo === "undefined") {
        console.warn("Echo belum ke-load");
        return;
    }

    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (!userIdMeta) {
        console.warn("Meta user-id tidak ditemukan");
        return;
    }

    const userId = userIdMeta.content;
    const list = document.getElementById("notificationPageList");
    const notifSwitch = document.getElementById("notifSwitch");

    // =========================
    // SWITCH (optional, jangan matiin script)
    // =========================
    if (notifSwitch) {
        const enabled = localStorage.getItem("notif_enabled") === "true";
        notifSwitch.checked = enabled;

        notifSwitch.addEventListener("change", function () {
            const isEnabled = this.checked;

            localStorage.setItem("notif_enabled", isEnabled);

            if (isEnabled) {
                if (Notification.permission !== "granted") {
                    Notification.requestPermission().then((permission) => {
                        if (permission === "granted") {
                            alert("Notifikasi berhasil diaktifkan 🔔");
                        } else {
                            alert("Notifikasi ditolak oleh browser ❌");
                        }
                    });
                } else {
                    alert("Notifikasi diaktifkan 🔔");
                }
            } else {
                alert("Notifikasi dimatikan 🔕");
            }
        });
    }

    // =========================
    // LIST VALIDATION
    // =========================
    if (!list) {
        console.warn("Element #notificationPageList tidak ditemukan");
        return;
    }

    // =========================
    // ECHO LISTENER
    // =========================
    Echo.private(`App.Models.User.${userId}`).notification((notification) => {
        const enabled = localStorage.getItem("notif_enabled") === "true";

        // ❌ skip kalau OFF
        if (!enabled) return;

        // 🔔 popup chrome
        if (Notification.permission === "granted") {
            new Notification(notification.title, {
                body: notification.message,
                icon: notification.avatar || "/assets/img/logo.png",
            });
        }

        appendNotificationToPage(notification);
    });

    // =========================
    // FUNCTION
    // =========================
    function appendNotificationToPage(notification) {
        const emptyState = document.getElementById("emptyState");
        if (emptyState) emptyState.remove();

        const avatarHTML =
            notification.type === "user"
                ? `<img src="${notification.avatar}" class="rounded-circle" width="40" height="40" style="object-fit:cover;">`
                : `<span class="avatar-initial rounded-circle bg-label-${notification.color || "primary"}">
                        <i class="bx ${notification.icon || "bx-bell"}"></i>
                   </span>`;

        const itemHTML = `
        <li class="list-group-item d-flex justify-content-between align-items-start notif-item"
            data-id="${notification.id}"
            data-title="${notification.title}"
            data-message="${notification.message}"
            data-avatar="${notification.avatar ?? ""}"
            data-type="${notification.type}"
            data-icon="${notification.icon ?? ""}"
            data-color="${notification.color ?? ""}">

            <div class="d-flex gap-3">
                <div>
                    <div class="avatar">
                        ${avatarHTML}
                    </div>
                </div>

                <div>
                    <div class="fw-semibold">
                        ${notification.title || "Notification"}
                    </div>

                    <small class="text-muted">
                        Baru saja
                    </small>
                </div>
            </div>

            <div class="d-flex gap-2">
                <span class="badge bg-primary">New</span>
            </div>
        </li>
        `;

        list.insertAdjacentHTML("afterbegin", itemHTML);

        if (list.children.length > 15) {
            list.removeChild(list.lastElementChild);
        }
    }
});

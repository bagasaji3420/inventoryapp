document.addEventListener("DOMContentLoaded", function () {
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (!userIdMeta) return;

    const userId = userIdMeta.content;

    Echo.private(`App.Models.User.${userId}`).notification((notification) => {
        // ── Sound ──
        const audio = new Audio("/assets/audio/notif.wav");
        audio.play().catch(() => {});

        console.log("[Home Notif]", notification);

        // ── Badge bell ──
        const badge = document.getElementById("homeNotifBadge");
        if (badge) badge.style.display = "inline-block";

        // ── Counter ──
        const countEl = document.getElementById("homeNotifCount");
        if (countEl) {
            let current = parseInt(countEl.innerText) || 0;
            countEl.innerText = current + 1 + " New";
        }

        // ── Append item ke list ──
        const list = document.getElementById("homeNotifList");
        if (!list) return;

        const avatarHtml =
            notification.type === "comment" || notification.type === "mention"
                ? `<img src="${notification.avatar ?? ""}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">`
                : `<span class="avatar-initial rounded-circle bg-label-${notification.color ?? "primary"}">
                    <i class="icon-base bx ${notification.icon ?? "bx-bell"}"></i>
               </span>`;

        const item = `
            <li class="list-group-item list-group-item-action dropdown-notifications-item notif-item px-4 py-3"
                style="cursor: pointer;"
                data-id="${notification.id ?? ""}"
                data-title="${notification.title ?? ""}"
                data-message="${notification.message ?? ""}"
                data-avatar="${notification.avatar ?? ""}"
                data-type="${notification.type ?? ""}"
                data-icon="${notification.icon ?? "bx-bell"}"
                data-color="${notification.color ?? "primary"}"
                data-url="${notification.url ?? ""}">

                <div class="d-flex align-items-start gap-3">

                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm">
                            ${avatarHtml}
                        </div>
                    </div>

                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="small fw-semibold mb-1 text-truncate">
                            ${notification.title ?? "Notification"}
                        </h6>
                        ${
                            notification.message
                                ? `<p class="text-muted mb-1 text-truncate" style="font-size: 0.78rem; line-height: 1.4;">
                                ${notification.message}
                               </p>`
                                : ""
                        }
                        <small class="text-body-secondary">Just now</small>
                    </div>

                    <span class="badge rounded-pill bg-primary flex-shrink-0 mt-1 p-1"
                          style="width: 8px; height: 8px;">
                    </span>

                </div>
            </li>
        `;

        list.insertAdjacentHTML("afterbegin", item);

        // ── Limit max 5 item ──
        if (list.children.length > 5) {
            list.removeChild(list.lastElementChild);
        }

        // ── Tampilkan footer "View all" kalau belum ada ──
        const dropdown = list.closest("ul.dropdown-menu");
        if (dropdown && !dropdown.querySelector(".border-top")) {
            dropdown.insertAdjacentHTML(
                "beforeend",
                `
                <li class="border-top">
                    <div class="d-grid p-3">
                        <a href="/notifications" class="btn btn-sm btn-outline-primary">
                            View all notifications
                        </a>
                    </div>
                </li>
            `,
            );
        }
    });
});

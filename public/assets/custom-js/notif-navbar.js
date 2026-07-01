const list = document.getElementById("notifList");
document.addEventListener("DOMContentLoaded", function () {
    const userId = document.querySelector('meta[name="user-id"]').content;

    Echo.private(`App.Models.User.${userId}`).notification((notification) => {
        
        const audio = new Audio("/assets/audio/notif.wav");
        
        console.log(notification);

        audio.play().catch(() => {});
        const badge = document.getElementById("notifBadge");
        if (badge) badge.style.display = "inline-block";
        const countEl = document.getElementById("notifCount");
        let current = parseInt(countEl.innerText) || 0;
        countEl.innerText = current + 1 + " New";
        const list = document.getElementById("notifList");

        const item = `
                <li class="list-group-item list-group-item-action dropdown-notifications-item notif-item"
                    data-id="${notification.id}"
                    data-title="${notification.title}"
                    data-message="${notification.message}"
                    data-avatar="${notification.avatar ?? ""}"
                    data-type="${notification.type}"
                    data-icon="${notification.icon ?? ""}"
                    data-url="${notification.url ?? ""}"
                    data-color="${notification.color ?? ""}">

                    <div class="d-flex">

                        <div class="shrink-0 me-3">
                            <div class="avatar">
                                ${
                                    notification.type === "user"
                                        ? `<img src="${notification.avatar}" class="rounded-circle" width="35" height="35" style="object-fit:cover;">`
                                        : `<span class="avatar-initial rounded-circle bg-label-${notification.color}">
                                                <i class="bx ${notification.icon}"></i>
                                        </span>`
                                }
                            </div>
                        </div>

                        <div class="grow">
                            <h6 class="small mb-0">${notification.title}</h6>
                            <small class="text-body-secondary">New</small>
                        </div>

                    </div>
                </li>
                `;

        list.insertAdjacentHTML("afterbegin", item);

        // 🔥 limit max 5 item
        if (list.children.length > 5) {
            list.removeChild(list.lastElementChild);
        }
    });
});

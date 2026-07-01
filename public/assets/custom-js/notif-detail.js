document.addEventListener("click", function (e) {
    const item = e.target.closest(".notif-item");
    if (!item) return;

    const title = item.dataset.title;
    const message = item.dataset.message;
    const avatar = item.dataset.avatar;
    const type = item.dataset.type;
    const icon = item.dataset.icon;
    const color = item.dataset.color;
    const url = item.dataset.url;

    const id = item.dataset.id;

    fetch(`/admin/notifications/${id}/read`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    });

    document.getElementById("modalMessage").innerText = message;
    document.getElementById("modalTitle").innerText = title;

    const modalAvatar = document.getElementById("modalAvatar");

    if (type === "user" || type === "comment" || type === "mention") {
        modalAvatar.innerHTML = `
            <img src="${avatar}" width="80" class="rounded-circle">
        `;
    } else {
        modalAvatar.innerHTML = `
            <span class="avatar-initial rounded-circle bg-label-${color}" style="width:80px;height:80px; display:flex; align-items:center; justify-content:center;">
                <i class="bx ${icon}" style="font-size:32px;"></i>
            </span>
        `;
    }

    // 🔥 BUTTON ACTION
    const actionBtn = document.getElementById("modalActionBtn");

    if (url && url !== "#") {
        actionBtn.href = url;
        actionBtn.classList.remove("d-none");
    } else {
        actionBtn.classList.add("d-none");
    }

    new bootstrap.Modal(document.getElementById("notifModal")).show();
});

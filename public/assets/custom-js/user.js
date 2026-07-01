document.querySelectorAll(".role-checkbox").forEach((checkbox) => {

    checkbox.addEventListener("change", function () {

        const checked = document.querySelectorAll(".role-checkbox:checked");

        // ❌ kalau tinggal 1 dan mau di-uncheck → blok
        if (checked.length === 0) {
            this.checked = true;

            alert("Min 1 role");
        } else if (checked.length === 3){
            this.checked = false;
            alert("Max 2 role");

        }
    });

});

function setUserEdit(id, roles, status, suspendTime, reason) {
    const form = document.getElementById("statusForm");

    // set action
    form.action = `/admin/users/${id}`;

    // reset checkbox
    document.querySelectorAll(".role-checkbox").forEach((cb) => {
        cb.checked = false;
    });

    // centang sesuai role user
    roles.forEach((role) => {
        const checkbox = document.querySelector(
            `.role-checkbox[value="${role}"]`,
        );
        if (checkbox) checkbox.checked = true;
    });

    // set status
    const select = document.getElementById("statusSelect");
    select.value = status;

    // set suspend time
    const suspend = document.getElementById("suspendTime");
    if (status === "suspend") {
        suspend.style.display = "block";
        suspend.value = formatDateTimeLocal(suspendTime);
    } else {
        suspend.style.display = "none";
        suspend.value = "";
    }

    // set reason
    const reasonField = document.getElementById("status_reason");
    if (status === "active") {
        reasonField.value = "";
    } else {
        reasonField.disabled = false;
        reasonField.value = reason ?? "";
    }
}

// show/hide suspend input
function toggleSuspend() {
    const status = document.getElementById("statusSelect").value;

    const suspendDiv = document.getElementById("suspendTime");
    const suspendInput = document.querySelector('[name="suspended_until"]');

    const reasonDiv = document.getElementById("Reason");
    const reasonInput = document.getElementById("status_reason");

    // reset dulu (biar aman)
    suspendDiv.style.display = "none";
    reasonDiv.style.display = "none";

    suspendInput.removeAttribute("required");
    reasonInput.removeAttribute("required");

    if (status === "suspend") {
        suspendDiv.style.display = "block";
        reasonDiv.style.display = "block";

        suspendInput.setAttribute("required", "required");
        reasonInput.setAttribute("required", "required");
    } else if (status === "banned") {
        reasonDiv.style.display = "block";
        reasonInput.setAttribute("required", "required");
    }
}



document
    .getElementById("statusSelect")
    .addEventListener("change", toggleSuspend);

document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".input-group-text");
    const input = document.querySelector("#password");

    toggle.addEventListener("click", function () {
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    });
});

function setEdit(
    id,
    name,
    permissions = [],
    icon = "",
    isAssignable = false,
    isProtected = false,
    isEditable = false,
) {
    const form = document.getElementById("editForm");

    form.action = `/admin/roles/${id}`;

    document.getElementById("editName").value = name ?? "";
    document.getElementById("editEmojiInput").value = icon ?? "";

    if (!Array.isArray(permissions)) permissions = [];

    document.querySelectorAll(".permission-item").forEach((cb) => {
        cb.checked = false;
    });

    document.querySelectorAll(".permission-item").forEach((cb) => {
        if (permissions.includes(cb.value)) {
            cb.checked = true;
        }
    });

    const assignable =
        isAssignable === true || isAssignable === "true" || isAssignable == 1;
    const protectedRole =
        isProtected === true || isProtected === "true" || isProtected == 1;
    const editableRole =
        isEditable === true || isEditable === "true" || isEditable == 1;

    document.getElementById("editIsAssignable").checked = assignable;
    document.getElementById("editIsProtected").checked = protectedRole;
    document.getElementById("editIsEditable").checked = editableRole;
}

// 🔥 reusable emoji handler
function initEmojiPicker(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);

    if (!input || !dropdown) return;

    const picker = dropdown.querySelector("emoji-picker");

    input.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.style.display =
            dropdown.style.display === "none" ? "block" : "none";
    });

    picker.addEventListener("emoji-click", (event) => {
        input.value = event.detail.unicode;
        dropdown.style.display = "none";
    });

    document.addEventListener("click", (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    // ✅ ADD
    initEmojiPicker("emojiInput", "emojiDropdown");

    // ✅ EDIT
    initEmojiPicker("editEmojiInput", "editEmojiDropdown");
});

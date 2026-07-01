let originalContainer = null;

document.addEventListener("DOMContentLoaded", function () {
    originalContainer = document.getElementById("mainFormWrapper");
});

function replyTo(commentId, username, el) {
    const form = document.getElementById("mainCommentForm");

    // 🔥 cari root dari element yg diklik
    const rootEl = el.closest("[data-root]");
    if (!rootEl) return;

    const rootId = rootEl.dataset.root;

    const target = document.getElementById("reply-form-" + rootId);
    if (!target) return;

    // pindahin form
    target.appendChild(form);

    // set parent_id
    document.getElementById("parent_id").value = commentId;

    // isi mention
    const input = document.getElementById("comment_input");
    input.value = "@" + username + " ";
    input.focus();

    // scroll smooth TANPA ke atas
    setTimeout(() => {
        form.scrollIntoView({
            behavior: "smooth",
            block: "center",
        });
    }, 100);
}

function cancelReply() {
    const form = document.getElementById("mainCommentForm");

    // balik ke atas
    originalContainer.appendChild(form);

    // reset
    document.getElementById("parent_id").value = "";
    document.getElementById("comment_input").value = "";
}

function deleteComment(id) {
    Swal.fire({
        title: "Delete comment?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById("deleteCommentForm");
            form.action = `/admin/articles/comments/remove/${id}`; // 🔥 route delete kamu
            form.submit();
        }
    });
}

let originalContainer = null;

document.addEventListener("DOMContentLoaded", function () {
    originalContainer = document.getElementById("mainFormWrapper");
});

function replyTo(commentId, username, el) {
    const form = document.getElementById("mainCommentForm");

    // Cari root dari element yang diklik
    const rootEl = el.closest("[data-root]");
    if (!rootEl) return;

    const rootId = rootEl.dataset.root;

    const target = document.getElementById("reply-form-" + rootId);
    if (!target) return;

    // Pindahkan form ke slot reply
    target.appendChild(form);

    // Set parent_id
    document.getElementById("parent_id").value = commentId;

    // Isi mention di textarea
    const input = document.getElementById("comment_input");
    input.value = "@" + username + " ";
    input.focus();

    // Scroll smooth ke form
    setTimeout(() => {
        form.scrollIntoView({
            behavior: "smooth",
            block: "center",
        });
    }, 100);
}

function cancelReply() {
    const form = document.getElementById("mainCommentForm");

    // Kembalikan form ke posisi semula
    originalContainer.appendChild(form);

    // Reset
    document.getElementById("parent_id").value = "";
    document.getElementById("comment_input").value = "";
}

function deleteComment(id) {
    Swal.fire({
        title: "Delete comment?",
        // text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById("deleteCommentForm");
            form.action = `/admin/articles/comments/remove/${id}`; 
            form.submit();
        }
    });
}

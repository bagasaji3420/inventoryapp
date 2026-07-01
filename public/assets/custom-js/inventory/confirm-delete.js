document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form.delete-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            Swal.fire({
                title: "Hapus data ini?",
                text: "Data yang dihapus tidak bisa dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger me-2",
                    cancelButton: "btn btn-secondary",
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

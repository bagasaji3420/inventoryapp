function setEdit(id, namaJenis) {
    const form = document.getElementById("editItemTypeForm");
    form.action = `/admin/item-types/${id}`;

    document.getElementById("editNamaJenis").value = namaJenis ?? "";
}

document.addEventListener("DOMContentLoaded", () => {
    $("#itemTypeTable").DataTable({
        responsive: true,
        language: {
            paginate: {
                previous: '<i class="bx bx-chevron-left"></i>',
                next: '<i class="bx bx-chevron-right"></i>',
            },
        },
    });
});

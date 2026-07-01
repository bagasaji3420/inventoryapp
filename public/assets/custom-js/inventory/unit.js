function setEdit(id, namaSatuan) {
    const form = document.getElementById("editUnitForm");
    form.action = `/admin/units/${id}`;

    document.getElementById("editNamaSatuan").value = namaSatuan ?? "";
}

document.addEventListener("DOMContentLoaded", () => {
    $("#unitTable").DataTable({
        responsive: true,
        language: {
            paginate: {
                previous: '<i class="bx bx-chevron-left"></i>',
                next: '<i class="bx bx-chevron-right"></i>',
            },
        },
    });
});

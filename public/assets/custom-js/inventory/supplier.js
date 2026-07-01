function setEdit(id, namaSupplier, kontakPerson, telepon, alamat) {
    const form = document.getElementById("editSupplierForm");
    form.action = `/admin/suppliers/${id}`;

    document.getElementById("editNamaSupplier").value = namaSupplier ?? "";
    document.getElementById("editKontakPerson").value = kontakPerson ?? "";
    document.getElementById("editTelepon").value = telepon ?? "";
    document.getElementById("editAlamat").value = alamat ?? "";
}

document.addEventListener("DOMContentLoaded", () => {
    $("#supplierTable").DataTable({
        responsive: true,
        language: {
            paginate: {
                previous: '<i class="bx bx-chevron-left"></i>',
                next: '<i class="bx bx-chevron-right"></i>',
            },
        },
    });
});

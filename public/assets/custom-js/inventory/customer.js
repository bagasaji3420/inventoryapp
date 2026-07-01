function setEdit(id, namaPelanggan, email, telepon, alamat) {
    const form = document.getElementById("editCustomerForm");
    form.action = `/admin/customers/${id}`;

    document.getElementById("editNamaPelanggan").value = namaPelanggan ?? "";
    document.getElementById("editEmail").value = email ?? "";
    document.getElementById("editTeleponCustomer").value = telepon ?? "";
    document.getElementById("editAlamatCustomer").value = alamat ?? "";
}

document.addEventListener("DOMContentLoaded", () => {
    $("#customerTable").DataTable({
        responsive: true,
        language: {
            paginate: {
                previous: '<i class="bx bx-chevron-left"></i>',
                next: '<i class="bx bx-chevron-right"></i>',
            },
        },
    });
});

function setEdit(id, kodeBarang, barcode, namaBarang, satuanId, jenisId, harga, stokMinimum, fotoUrl) {
    const form = document.getElementById("editItemForm");
    form.action = `/admin/items/${id}`;

    document.getElementById("editKodeBarang").value = kodeBarang ?? "";
    document.getElementById("editBarcode").value = barcode ?? "";
    document.getElementById("editNamaBarang").value = namaBarang ?? "";
    document.getElementById("editSatuanId").value = satuanId ?? "";
    document.getElementById("editJenisId").value = jenisId ?? "";
    document.getElementById("editHarga").value = harga ?? "";
    document.getElementById("editStokMinimum").value = stokMinimum ?? "";

    document.getElementById("editFoto").value = "";

    const preview = document.getElementById("editFotoPreview");
    if (fotoUrl) {
        preview.src = fotoUrl;
        preview.classList.remove("d-none");
    } else {
        preview.src = "";
        preview.classList.add("d-none");
    }
}

function previewFoto(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!input.files || !input.files[0]) {
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove("d-none");
    };
    reader.readAsDataURL(input.files[0]);
}

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("addFoto").addEventListener("change", function () {
        previewFoto("addFoto", "addFotoPreview");
    });

    document.getElementById("editFoto").addEventListener("change", function () {
        previewFoto("editFoto", "editFotoPreview");
    });

    document.getElementById("addItemModal").addEventListener("hidden.bs.modal", function () {
        document.getElementById("addFoto").value = "";
        const preview = document.getElementById("addFotoPreview");
        preview.src = "";
        preview.classList.add("d-none");
    });

    $("#itemTable").DataTable({
        responsive: true,
        language: {
            paginate: {
                previous: '<i class="bx bx-chevron-left"></i>',
                next: '<i class="bx bx-chevron-right"></i>',
            },
        },
    });
});

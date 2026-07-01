(function () {
    let rowIndex = 0;

    function itemOptions() {
        return window.inventoryItems
            .map(
                (item) =>
                    `<option value="${item.id}" data-satuan-id="${item.satuan_id}">${item.nama_barang}</option>`
            )
            .join("");
    }

    function unitOptions() {
        return window.inventoryUnits
            .map((unit) => `<option value="${unit.id}">${unit.nama_satuan}</option>`)
            .join("");
    }

    function addRow() {
        const tbody = document.getElementById("stockOutItemsBody");
        const index = rowIndex++;

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <select name="items[${index}][item_id]" class="form-select item-select" required>
                    <option value="">Pilih Barang</option>
                    ${itemOptions()}
                </select>
            </td>
            <td>
                <select name="items[${index}][unit_id]" class="form-select unit-select" required>
                    <option value="">Pilih Satuan</option>
                    ${unitOptions()}
                </select>
            </td>
            <td>
                <input type="number" step="0.01" min="0.01" name="items[${index}][qty_input]" class="form-control" required>
            </td>
            <td>
                <button type="button" class="btn btn-icon btn-danger btn-sm shadow-none remove-row">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        const itemSelect = tr.querySelector(".item-select");
        const unitSelect = tr.querySelector(".unit-select");

        itemSelect.addEventListener("change", function () {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const satuanId = selectedOption.getAttribute("data-satuan-id");
            if (satuanId) {
                unitSelect.value = satuanId;
            }
        });

        tr.querySelector(".remove-row").addEventListener("click", function () {
            tr.remove();
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("addStockOutRow").addEventListener("click", addRow);
        addRow();

        document.getElementById("addStockOutModal").addEventListener("hidden.bs.modal", function () {
            document.getElementById("stockOutItemsBody").innerHTML = "";
            rowIndex = 0;
            addRow();
        });
    });
})();

(function () {
    let rowIndex = 0;

    function getSelectedItemIds(exceptSelect) {
        return Array.from(document.querySelectorAll("#stockOpnameItemsBody .item-select"))
            .filter((select) => select !== exceptSelect)
            .map((select) => select.value)
            .filter((value) => value !== "");
    }

    function itemOptions(currentSelect) {
        const usedIds = getSelectedItemIds(currentSelect);

        return window.inventoryItems
            .filter((item) => !usedIds.includes(String(item.id)))
            .map((item) => `<option value="${item.id}" data-stok="${item.stok}">${item.nama_barang}</option>`)
            .join("");
    }

    function refreshAllSelects() {
        document.querySelectorAll("#stockOpnameItemsBody .item-select").forEach((select) => {
            const currentValue = select.value;
            select.innerHTML = `<option value="">Pilih Barang</option>${itemOptions(select)}`;
            select.value = currentValue;
        });

        const allUsed = getSelectedItemIds(null).length >= window.inventoryItems.length;
        document.getElementById("addStockOpnameRow").disabled = allUsed;
    }

    function addRow() {
        if (getSelectedItemIds(null).length >= window.inventoryItems.length) {
            return;
        }

        const tbody = document.getElementById("stockOpnameItemsBody");
        const index = rowIndex++;

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <select name="items[${index}][item_id]" class="form-select item-select" required>
                    <option value="">Pilih Barang</option>
                    ${itemOptions(null)}
                </select>
            </td>
            <td>
                <input type="text" class="form-control stok-sistem" disabled placeholder="-">
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${index}][stok_fisik]" class="form-control" required>
            </td>
            <td>
                <button type="button" class="btn btn-icon btn-danger btn-sm shadow-none remove-row">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        const itemSelect = tr.querySelector(".item-select");
        const stokSistem = tr.querySelector(".stok-sistem");

        itemSelect.addEventListener("change", function () {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            stokSistem.value = selectedOption.getAttribute("data-stok") ?? "";
            refreshAllSelects();
        });

        tr.querySelector(".remove-row").addEventListener("click", function () {
            tr.remove();
            refreshAllSelects();
        });

        refreshAllSelects();
    }

    // ── Barcode scan ────────────────────────────────────────────────
    let html5QrCode = null;

    function setBarcodeStatus(msg, isSuccess) {
        const el = document.getElementById("opnameBarcodeStatus");
        el.textContent = msg;
        el.classList.remove("d-none", "text-success", "text-danger");
        el.classList.add(isSuccess ? "text-success" : "text-danger");
        clearTimeout(el._timer);
        el._timer = setTimeout(() => { el.classList.add("d-none"); el.textContent = ""; }, 2000);
    }

    function handleBarcodeScan(barcode) {
        if (!barcode) return;
        const input = document.getElementById("opnameBarcodeInput");
        const item = window.inventoryItems.find((i) => i.barcode === barcode);

        if (!item) {
            setBarcodeStatus("Barcode tidak ditemukan", false);
            input.classList.add("is-invalid");
            setTimeout(() => input.classList.remove("is-invalid"), 1500);
            input.value = "";
            return;
        }

        // Kalau item sudah ada di baris, focus ke stok fisik baris itu
        const existing = Array.from(
            document.querySelectorAll("#stockOpnameItemsBody .item-select")
        ).find((sel) => sel.value === String(item.id));

        if (existing) {
            setBarcodeStatus("✓ " + item.nama_barang + " (sudah ada)", true);
            existing.closest("tr").querySelector("input[type='number']").focus();
        } else {
            addRow();
            const rows = document.querySelectorAll("#stockOpnameItemsBody .item-select");
            const lastSelect = rows[rows.length - 1];
            lastSelect.value = String(item.id);
            lastSelect.dispatchEvent(new Event("change"));
            setBarcodeStatus("✓ " + item.nama_barang, true);
            lastSelect.closest("tr").querySelector("input[type='number']").focus();
        }

        input.value = "";
    }

    function startCameraScanner() {
        const container = document.getElementById("opnameCameraContainer");
        const btn = document.getElementById("opnameCameraBtn");
        container.classList.remove("d-none");
        btn.innerHTML = '<i class="bx bx-camera-off fs-5"></i>';

        html5QrCode = new Html5Qrcode("opnameCameraReader", { verbose: false });
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 15, experimentalFeatures: { useBarCodeDetectorIfSupported: true } },
            (decodedText) => { stopCameraScanner(); handleBarcodeScan(decodedText.trim()); },
            () => {}
        ).catch(() => { stopCameraScanner(); setBarcodeStatus("Kamera tidak dapat diakses", false); });
    }

    function stopCameraScanner() {
        const container = document.getElementById("opnameCameraContainer");
        const btn = document.getElementById("opnameCameraBtn");
        if (html5QrCode) {
            html5QrCode.stop().then(() => html5QrCode.clear()).catch(() => {});
            html5QrCode = null;
        }
        container.classList.add("d-none");
        btn.innerHTML = '<i class="bx bx-camera fs-5"></i>';
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("addStockOpnameRow").addEventListener("click", addRow);
        addRow();

        document.getElementById("opnameBarcodeInput").addEventListener("keydown", function (e) {
            if (e.key === "Enter") { e.preventDefault(); handleBarcodeScan(this.value.trim()); }
        });

        document.getElementById("opnameCameraBtn").addEventListener("click", function () {
            html5QrCode ? stopCameraScanner() : startCameraScanner();
        });

        document.getElementById("opnameStopCameraBtn").addEventListener("click", stopCameraScanner);

        document.getElementById("addStockOpnameModal").addEventListener("hidden.bs.modal", function () {
            stopCameraScanner();
            document.getElementById("stockOpnameItemsBody").innerHTML = "";
            document.getElementById("opnameBarcodeInput").value = "";
            document.getElementById("opnameBarcodeStatus").classList.add("d-none");
            rowIndex = 0;
            addRow();
        });

        document.getElementById("addStockOpnameModal").addEventListener("shown.bs.modal", function () {
            document.getElementById("opnameBarcodeInput").focus();
        });
    });
})();

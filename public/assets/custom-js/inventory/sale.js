(function () {
    let rowIndex = 0;

    function formatRupiah(value) {
        return "Rp " + Number(value).toLocaleString("id-ID");
    }

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll("#saleCartBody tr").forEach((tr) => {
            total += Number(tr.dataset.subtotal || 0);
        });

        document.getElementById("saleTotalDisplay").textContent = formatRupiah(total);
        document.getElementById("saleTotalDisplay").dataset.total = total;

        recalcKembalian();
    }

    function recalcKembalian() {
        const total = Number(document.getElementById("saleTotalDisplay").dataset.total || 0);
        const bayar = Number(document.getElementById("saleBayar").value || 0);
        const kembalian = Math.max(bayar - total, 0);

        document.getElementById("saleKembalian").value = formatRupiah(kembalian);
    }

    function updateStokInfo() {
        const itemSelect = document.getElementById("saleItemSelect");
        const qtyInput = document.getElementById("saleItemQty");
        const info = document.getElementById("saleItemStokInfo");

        const opt = itemSelect.options[itemSelect.selectedIndex];
        if (!itemSelect.value) {
            info.classList.add("d-none");
            qtyInput.removeAttribute("max");
            return;
        }

        const stok = Number(opt.getAttribute("data-stok") || 0);
        const satuan = opt.getAttribute("data-satuan") || "";
        info.textContent = `Stok tersedia: ${stok.toLocaleString("id-ID")} ${satuan}`;
        info.classList.remove("d-none");
        info.className = info.className.replace(/text-\w+/g, "");
        info.classList.add(stok <= 0 ? "text-danger" : "text-muted");
        qtyInput.max = stok;
    }

    function addCartRow() {
        const itemSelect = document.getElementById("saleItemSelect");
        const qtyInput = document.getElementById("saleItemQty");

        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const itemId = itemSelect.value;
        const qty = Number(qtyInput.value);

        if (!itemId || qty <= 0) {
            return;
        }

        const stok = Number(selectedOption.getAttribute("data-stok") || 0);
        const satuan = selectedOption.getAttribute("data-satuan") || "";
        if (qty > stok) {
            const info = document.getElementById("saleItemStokInfo");
            info.textContent = `Stok tidak cukup! Tersedia: ${stok.toLocaleString("id-ID")} ${satuan}`;
            info.className = "text-danger mt-1 d-block";
            qtyInput.value = stok;
            return;
        }

        const harga = Number(selectedOption.getAttribute("data-harga"));
        const nama = selectedOption.getAttribute("data-nama");
        const subtotal = harga * qty;
        const index = rowIndex++;

        const tr = document.createElement("tr");
        tr.dataset.subtotal = subtotal;
        tr.innerHTML = `
            <td>
                ${nama}
                <input type="hidden" name="items[${index}][item_id]" value="${itemId}">
            </td>
            <td>
                ${qty}
                <input type="hidden" name="items[${index}][qty]" value="${qty}">
            </td>
            <td>${formatRupiah(harga)}</td>
            <td>${formatRupiah(subtotal)}</td>
            <td>
                <button type="button" class="btn btn-icon btn-danger btn-sm shadow-none remove-row">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        tr.querySelector(".remove-row").addEventListener("click", function () {
            tr.remove();
            recalcTotal();
        });

        document.getElementById("saleCartBody").appendChild(tr);

        itemSelect.value = "";
        qtyInput.value = 1;
        qtyInput.removeAttribute("max");
        document.getElementById("saleItemStokInfo").classList.add("d-none");

        recalcTotal();
    }

    // ── Camera scanner ──────────────────────────────────────────────
    let html5QrCode = null;

    function startCameraScanner() {
        const container = document.getElementById("cameraScanContainer");
        const btn = document.getElementById("cameraScanBtn");

        container.classList.remove("d-none");
        btn.classList.add("active");
        btn.innerHTML = '<i class="bx bx-camera-off fs-5"></i>';

        html5QrCode = new Html5Qrcode("cameraReader", { verbose: false });

        html5QrCode
            .start(
                { facingMode: "environment" },
                {
                    fps: 15,
                    experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                },
                (decodedText) => {
                    stopCameraScanner();
                    handleBarcodeScan(decodedText.trim());
                },
                () => {}
            )
            .catch(() => {
                stopCameraScanner();
                setBarcodeStatus("Kamera tidak dapat diakses", false);
            });
    }

    function stopCameraScanner() {
        const container = document.getElementById("cameraScanContainer");
        const btn = document.getElementById("cameraScanBtn");

        if (html5QrCode) {
            html5QrCode
                .stop()
                .then(() => html5QrCode.clear())
                .catch(() => {});
            html5QrCode = null;
        }

        container.classList.add("d-none");
        btn.classList.remove("active");
        btn.innerHTML = '<i class="bx bx-camera fs-5"></i>';
    }

    // ── Barcode scan logic ──────────────────────────────────────────
    function findOptionByBarcode(barcode) {
        const select = document.getElementById("saleItemSelect");
        for (const option of select.options) {
            if (option.getAttribute("data-barcode") === barcode) {
                return option;
            }
        }
        return null;
    }

    function setBarcodeStatus(msg, isSuccess) {
        const el = document.getElementById("barcodeStatus");
        el.textContent = msg;
        el.classList.remove("d-none", "text-success", "text-danger");
        el.classList.add(isSuccess ? "text-success" : "text-danger");

        clearTimeout(el._timer);
        el._timer = setTimeout(() => {
            el.classList.add("d-none");
            el.textContent = "";
        }, 2000);
    }

    function handleBarcodeScan(barcode) {
        if (!barcode) return;

        const option = findOptionByBarcode(barcode);
        const barcodeInput = document.getElementById("saleBarcodeInput");

        if (!option) {
            setBarcodeStatus("Barcode tidak ditemukan", false);
            barcodeInput.classList.add("is-invalid");
            setTimeout(() => barcodeInput.classList.remove("is-invalid"), 1500);
            barcodeInput.value = "";
            return;
        }

        const select = document.getElementById("saleItemSelect");
        select.value = option.value;
        updateStokInfo();

        setBarcodeStatus("✓ " + option.getAttribute("data-nama"), true);
        barcodeInput.value = "";

        const qtyInput = document.getElementById("saleItemQty");
        qtyInput.value = 1;
        qtyInput.select();
        qtyInput.focus();
    }

    // ── DOMContentLoaded ────────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("addSaleCartRow").addEventListener("click", addCartRow);
        document.getElementById("saleItemSelect").addEventListener("change", updateStokInfo);
        document.getElementById("saleBayar").addEventListener("input", recalcKembalian);

        // Enter on qty field → add cart row then refocus barcode input
        document.getElementById("saleItemQty").addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                addCartRow();
                document.getElementById("saleBarcodeInput").focus();
            }
        });

        // Barcode input: Enter → process scan
        document.getElementById("saleBarcodeInput").addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                handleBarcodeScan(this.value.trim());
            }
        });

        document.getElementById("cameraScanBtn").addEventListener("click", function () {
            if (html5QrCode) {
                stopCameraScanner();
            } else {
                startCameraScanner();
            }
        });

        document.getElementById("stopCameraBtn").addEventListener("click", stopCameraScanner);

        document.getElementById("addSaleModal").addEventListener("hidden.bs.modal", function () {
            stopCameraScanner();
            document.getElementById("saleCartBody").innerHTML = "";
            document.getElementById("saleBayar").value = 0;
            document.getElementById("saleBarcodeInput").value = "";
            document.getElementById("barcodeStatus").classList.add("d-none");
            document.getElementById("saleItemSelect").value = "";
            rowIndex = 0;
            recalcTotal();
        });

        // Auto-focus barcode input when modal opens
        document.getElementById("addSaleModal").addEventListener("shown.bs.modal", function () {
            document.getElementById("saleBarcodeInput").focus();
        });

        recalcTotal();
    });
})();

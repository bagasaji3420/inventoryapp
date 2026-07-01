<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Barcode - {{ $item->nama_barang }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background: #f4f4f4;
        }

        .toolbar {
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar input[type="number"] {
            width: 70px;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .toolbar .toggle {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .toolbar button {
            padding: 7px 16px;
            border: none;
            border-radius: 4px;
            background: #696cff;
            color: #fff;
            cursor: pointer;
        }

        .labels {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .label {
            width: 200px;
            border: 1px solid #999;
            border-radius: 4px;
            background: #fff;
            padding: 8px;
            text-align: center;
        }

        .label .nama {
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .label .kode {
            font-size: 10px;
            color: #555;
        }

        .label .harga {
            font-size: 11px;
            font-weight: bold;
            margin-top: 2px;
        }

        .label .harga.hidden {
            display: none;
        }

        .label svg {
            max-width: 100%;
        }

        @media print {
            body {
                background: #fff;
                margin: 0;
            }

            .toolbar {
                display: none;
            }

            .label {
                border: 1px dashed #999;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <form class="toolbar" method="GET" action="{{ route('items.barcode', $item->id) }}">
        <label for="jumlah">Jumlah label:</label>
        <input type="number" id="jumlah" name="jumlah" min="1" max="100" value="{{ $jumlah }}">

        <label class="toggle">
            <input type="hidden" name="tampilkan_harga" value="0">
            <input type="checkbox" id="tampilkan_harga" name="tampilkan_harga" value="1"
                @checked($tampilkanHarga)>
            Tampilkan Harga
        </label>

        <button type="submit">Terapkan</button>
        <button type="button" onclick="window.print()">Cetak</button>
    </form>

    <div class="labels">
        @for ($i = 0; $i < $jumlah; $i++)
            <div class="label">
                <div class="nama">{{ $item->nama_barang }}</div>
                <div class="kode">{{ $item->kode_barang }}</div>
                <svg class="barcode" data-value="{{ $item->barcode }}"></svg>
                <div class="harga {{ $tampilkanHarga ? '' : 'hidden' }}">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </div>
            </div>
        @endfor
    </div>

    <script>
        document.querySelectorAll('.barcode').forEach(function (el) {
            JsBarcode(el, el.dataset.value, {
                format: 'CODE128',
                width: 1.5,
                height: 40,
                fontSize: 12,
                margin: 4,
            });
        });

        document.getElementById('tampilkan_harga').addEventListener('change', function () {
            var checked = this.checked;
            document.querySelectorAll('.label .harga').forEach(function (el) {
                el.classList.toggle('hidden', !checked);
            });
        });
    </script>
</body>

</html>

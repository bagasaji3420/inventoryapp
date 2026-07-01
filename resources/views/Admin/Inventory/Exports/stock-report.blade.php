<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Sisa Stok</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; padding: 20px 30px; }

        /* Header kop */
        .kop { width: 100%; margin-bottom: 12px; }
        .kop td { border: none; padding: 0; vertical-align: middle; }
        .kop-logo { width: 70px; }
        .kop-logo img { max-width: 60px; max-height: 60px; }
        .kop-text { text-align: center; }
        .kop-text h2 { margin: 0; font-size: 18px; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0; font-size: 10px; color: #555; }
        .kop-right { width: 70px; text-align: right; font-size: 9px; color: #999; }

        hr { border: none; border-top: 2px solid #222; margin: 8px 0 4px; }
        hr.thin { border-top: 1px solid #ccc; margin: 2px 0 12px; }

        .doc-title { text-align: center; font-size: 13px; font-weight: bold; margin: 8px 0 2px; }
        .doc-sub { text-align: center; font-size: 10px; color: #666; margin-bottom: 14px; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 4px; }
        table.data th {
            background: #222;
            color: #fff;
            padding: 6px 7px;
            text-align: left;
            font-size: 10px;
        }
        table.data td {
            padding: 5px 7px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
            vertical-align: middle;
        }
        table.data tr:nth-child(even) td { background: #f9f9f9; }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-aman    { background: #d4edda; color: #155724; }
        .badge-rendah  { background: #fff3cd; color: #856404; }
        .badge-habis   { background: #f8d7da; color: #721c24; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .summary { margin-bottom: 14px; }
        .summary table { width: 100%; border: none; }
        .summary td { border: none; padding: 2px 6px; font-size: 10px; }
        .summary .box {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }

        .footer { margin-top: 30px; font-size: 9px; color: #aaa; text-align: center; }
    </style>
</head>
<body>

    {{-- KOP --}}
    <table class="kop">
        <tr>
            <td class="kop-logo">
                @if ($settings->logo)
                    <img src="{{ storage_path('app/public/' . $settings->logo) }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <h2>{{ $settings->nama_perusahaan }}</h2>
                @if ($settings->alamat_perusahaan)
                    <p>{{ $settings->alamat_perusahaan }}</p>
                @endif
            </td>
            <td class="kop-right">{{ $tanggal }}</td>
        </tr>
    </table>
    <hr>
    <hr class="thin">

    <div class="doc-title">LAPORAN SISA STOK BARANG</div>
    <div class="doc-sub">Per Tanggal {{ $tanggal }}</div>

    {{-- Ringkasan --}}
    @php
        $totalItem  = $items->count();
        $aman       = $items->filter(fn($i) => $i->stok > $i->stok_minimum)->count();
        $rendah     = $items->filter(fn($i) => $i->stok > 0 && $i->stok <= $i->stok_minimum)->count();
        $habis      = $items->filter(fn($i) => $i->stok <= 0)->count();
    @endphp
    <table style="width:100%;margin-bottom:14px;">
        <tr>
            <td style="width:25%;text-align:center;padding:4px;">
                <div style="background:#e8f4fd;border-radius:6px;padding:6px 0;">
                    <div style="font-size:16px;font-weight:bold;">{{ $totalItem }}</div>
                    <div style="font-size:9px;color:#555;">Total Barang</div>
                </div>
            </td>
            <td style="width:25%;text-align:center;padding:4px;">
                <div style="background:#d4edda;border-radius:6px;padding:6px 0;">
                    <div style="font-size:16px;font-weight:bold;color:#155724;">{{ $aman }}</div>
                    <div style="font-size:9px;color:#155724;">Stok Aman</div>
                </div>
            </td>
            <td style="width:25%;text-align:center;padding:4px;">
                <div style="background:#fff3cd;border-radius:6px;padding:6px 0;">
                    <div style="font-size:16px;font-weight:bold;color:#856404;">{{ $rendah }}</div>
                    <div style="font-size:9px;color:#856404;">Stok Rendah</div>
                </div>
            </td>
            <td style="width:25%;text-align:center;padding:4px;">
                <div style="background:#f8d7da;border-radius:6px;padding:6px 0;">
                    <div style="font-size:16px;font-weight:bold;color:#721c24;">{{ $habis }}</div>
                    <div style="font-size:9px;color:#721c24;">Stok Habis</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Tabel --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:25px;">#</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th class="text-center">Satuan</th>
                <th class="text-right">Min. Stok</th>
                <th class="text-right">Sisa Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i => $item)
                @php
                    if ($item->stok <= 0) {
                        $status = 'habis'; $label = 'Habis';
                    } elseif ($item->stok <= $item->stok_minimum) {
                        $status = 'rendah'; $label = 'Rendah';
                    } else {
                        $status = 'aman'; $label = 'Aman';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong></td>
                    <td>{{ $item->jenis->nama_jenis ?? '-' }}</td>
                    <td class="text-center">{{ $item->satuan->nama_satuan ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->stok_minimum, 0) }}</td>
                    <td class="text-right"><strong>{{ number_format($item->stok, 2) }}</strong></td>
                    <td class="text-center">
                        <span class="badge badge-{{ $status }}">{{ $label }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh sistem {{ $settings->nama_perusahaan }} · {{ $tanggal }}
    </div>

</body>
</html>

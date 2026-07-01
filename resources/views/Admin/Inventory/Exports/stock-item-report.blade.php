<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Stok - {{ $item->nama_barang }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; padding: 20px 30px; }

        .kop { width: 100%; margin-bottom: 10px; }
        .kop td { border: none; padding: 0; vertical-align: middle; }
        .kop-logo img { max-width: 58px; max-height: 58px; }
        .kop-text { text-align: center; }
        .kop-text h2 { margin: 0; font-size: 17px; }
        .kop-text p { margin: 1px 0; font-size: 10px; color: #555; }
        .kop-right { width: 70px; text-align: right; font-size: 9px; color: #999; }

        hr { border: none; border-top: 2px solid #222; margin: 7px 0 3px; }
        hr.thin { border-top: 1px solid #ccc; margin: 2px 0 10px; }

        .doc-title { text-align: center; font-size: 13px; font-weight: bold; margin: 6px 0 1px; }
        .doc-sub { text-align: center; font-size: 10px; color: #666; margin-bottom: 12px; }

        /* Info produk */
        .info-box { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .info-box td { border: 1px solid #ddd; padding: 5px 8px; font-size: 10px; }
        .info-box th { background: #f4f4f4; border: 1px solid #ddd; padding: 5px 8px; font-size: 10px; text-align: left; width: 100px; }

        /* Ringkasan */
        .summary { width: 100%; margin-bottom: 14px; border-collapse: collapse; }
        .summary td { border: none; padding: 3px 5px; text-align: center; }
        .sbox { border-radius: 5px; padding: 6px 4px; }

        /* Tabel */
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #222; color: #fff; padding: 6px 7px; font-size: 10px; text-align: left; }
        table.data td { padding: 5px 7px; border-bottom: 1px solid #e8e8e8; font-size: 10px; }
        table.data tr:nth-child(even) td { background: #fafafa; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .masuk  { background: #d4edda; color: #155724; }
        .keluar { background: #fff3cd; color: #856404; }
        .opname { background: #e2d9f3; color: #4a2c8a; }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .green { color: #155724; font-weight: bold; }
        .red   { color: #721c24; font-weight: bold; }

        .footer { margin-top: 24px; font-size: 9px; color: #aaa; text-align: center; }
    </style>
</head>
<body>

    <table class="kop">
        <tr>
            <td class="kop-logo" style="width:65px;">
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
    <hr><hr class="thin">

    <div class="doc-title">KARTU STOK BARANG</div>
    <div class="doc-sub">
        @if ($tanggalAwal || $tanggalAkhir)
            Periode: {{ $tanggalAwal ?? '...' }} s/d {{ $tanggalAkhir ?? '...' }}
        @else
            Semua Periode
        @endif
    </div>

    {{-- Info produk --}}
    <table class="info-box">
        <tr>
            <th>Kode Barang</th>
            <td>{{ $item->kode_barang }}</td>
            <th>Jenis</th>
            <td>{{ $item->jenis->nama_jenis ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td colspan="3"><strong>{{ $item->nama_barang }}</strong></td>
        </tr>
        <tr>
            <th>Satuan</th>
            <td>{{ $item->satuan->nama_satuan ?? '-' }}</td>
            <th>Stok Minimum</th>
            <td>{{ number_format($item->stok_minimum, 0) }}</td>
        </tr>
        <tr>
            <th>Stok Saat Ini</th>
            <td colspan="3">
                <strong style="font-size:13px;">{{ number_format($item->stok, 2) }}</strong>
                {{ $item->satuan->nama_satuan ?? '' }}
            </td>
        </tr>
    </table>

    {{-- Ringkasan pergerakan --}}
    <table class="summary">
        <tr>
            <td style="width:33%;">
                <div class="sbox" style="background:#d4edda;">
                    <div style="font-size:15px;font-weight:bold;color:#155724;">+{{ number_format($totalMasuk, 2) }}</div>
                    <div style="font-size:9px;color:#155724;">Total Masuk</div>
                </div>
            </td>
            <td style="width:33%;">
                <div class="sbox" style="background:#f8d7da;">
                    <div style="font-size:15px;font-weight:bold;color:#721c24;">-{{ number_format($totalKeluar, 2) }}</div>
                    <div style="font-size:9px;color:#721c24;">Total Keluar</div>
                </div>
            </td>
            <td style="width:33%;">
                <div class="sbox" style="background:#e8f4fd;">
                    <div style="font-size:15px;font-weight:bold;color:#1a5276;">{{ number_format($item->stok, 2) }}</div>
                    <div style="font-size:9px;color:#1a5276;">Saldo Akhir</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Tabel riwayat --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:25px;">#</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Referensi</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Keluar</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stockCards as $i => $sc)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($sc->tanggal)->format('Y-m-d H:i') }}</td>
                    <td>
                        <span class="badge {{ $sc->jenis_transaksi }}">
                            {{ ucfirst($sc->jenis_transaksi) }}
                        </span>
                    </td>
                    <td>{{ $sc->referensi }}</td>
                    <td class="text-right green">
                        {{ $sc->masuk ? number_format($sc->masuk, 2) : '-' }}
                    </td>
                    <td class="text-right red">
                        {{ $sc->keluar ? '-' . number_format($sc->keluar, 2) : '-' }}
                    </td>
                    <td class="text-right"><strong>{{ number_format($sc->saldo, 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color:#aaa;padding:12px;">
                        Tidak ada riwayat transaksi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh sistem {{ $settings->nama_perusahaan }} · {{ $tanggal }}
    </div>

</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h2 { margin-bottom: 0; }
        p.sub { margin-top: 2px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>

<body>
    <h2>Laporan Barang Masuk</h2>
    <p class="sub">
        Dicetak: {{ now()->format('Y-m-d H:i') }}
        @if ($tanggalAwal || $tanggalAkhir)
            &mdash; Periode: {{ $tanggalAwal ?? '...' }} s/d {{ $tanggalAkhir ?? '...' }}
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID Transaksi</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Jumlah Masuk</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($stockIns as $stockIn)
                @foreach ($stockIn->items as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $stockIn->no_transaksi }}</td>
                        <td>{{ $stockIn->supplier->nama_supplier }}</td>
                        <td>{{ $stockIn->tanggal->format('Y-m-d') }}</td>
                        <td>{{ $row->item->nama_barang }}</td>
                        <td>{{ $row->unit->nama_satuan }}</td>
                        <td>{{ $row->qty_input }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

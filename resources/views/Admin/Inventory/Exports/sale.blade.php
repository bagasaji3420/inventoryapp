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
        tfoot td { font-weight: bold; }
    </style>
</head>

<body>
    <h2>Laporan Penjualan</h2>
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
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $i => $sale)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $sale->no_faktur }}</td>
                    <td>{{ $sale->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $sale->customer->nama_pelanggan }}</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $sale->status_pembayaran)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total Keseluruhan</td>
                <td colspan="2">Rp {{ number_format($sales->sum('total'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>

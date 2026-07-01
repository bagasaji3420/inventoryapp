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
    <h2>Laporan Surat Jalan</h2>
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
                <th>No. Surat Jalan</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Alamat Tujuan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deliveryNotes as $i => $deliveryNote)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $deliveryNote->no_surat }}</td>
                    <td>{{ $deliveryNote->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $deliveryNote->stockOut->customer->nama_pelanggan }}</td>
                    <td>{{ $deliveryNote->alamat_tujuan }}</td>
                    <td>{{ ucfirst($deliveryNote->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $deliveryNote->no_surat }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; }
        .header { width: 100%; margin-bottom: 10px; }
        .header table { width: 100%; border: none; }
        .header table td { border: none; padding: 0; vertical-align: middle; }
        .header img.logo { max-width: 80px; max-height: 80px; }
        .header-text { text-align: center; }
        .header-text h2 { margin: 0; font-size: 22px; }
        .header-text p { margin: 2px 0; color: #555; }
        .header-text .address { font-size: 11px; }
        hr { border: none; border-top: 2px solid #333; margin: 10px 0 16px; }
        .info-table { width: 100%; margin-bottom: 16px; }
        .info-table td { padding: 2px 4px; vertical-align: top; }
        .label { width: 110px; color: #555; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.items th, table.items td { border: 1px solid #999; padding: 6px 8px; }
        table.items th { background: #f2f2f2; text-align: left; }
        table.items td.center { text-align: center; }
        .signatures { width: 100%; margin-top: 60px; }
        .signatures td { width: 33%; text-align: center; vertical-align: top; }
        .sign-space { height: 70px; }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                @if ($settings->logo)
                    <td style="width:90px;">
                        <img class="logo" src="{{ storage_path('app/public/' . $settings->logo) }}" alt="Logo">
                    </td>
                @endif
                <td class="header-text">
                    <h2>{{ $settings->nama_perusahaan }}</h2>
                    <p>Surat Jalan</p>
                    @if ($settings->alamat_perusahaan)
                        <p class="address">{{ $settings->alamat_perusahaan }}</p>
                    @endif
                </td>
                @if ($settings->logo)
                    <td style="width:90px;"></td>
                @endif
            </tr>
        </table>
    </div>

    <hr>

    <table class="info-table">
        <tr>
            <td class="label">No. Surat Jalan</td>
            <td>: <strong>{{ $deliveryNote->no_surat }}</strong></td>
            <td class="label">Tanggal</td>
            <td>: {{ $deliveryNote->tanggal->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td class="label">Kepada</td>
            <td colspan="3">: {{ $deliveryNote->stockOut->customer->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Tujuan</td>
            <td colspan="3">: {{ $deliveryNote->alamat_tujuan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Transaksi</td>
            <td colspan="3">: {{ $deliveryNote->stockOut->no_transaksi }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>Nama Barang</th>
                <th style="width:100px;">Satuan</th>
                <th style="width:100px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deliveryNote->stockOut->items as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $row->item->nama_barang }}</td>
                    <td class="center">{{ $row->unit->nama_satuan }}</td>
                    <td class="center">{{ $row->qty_input }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Dikirim oleh,
                <div class="sign-space"></div>
                ( ____________________ )
            </td>
            <td>
                Diketahui oleh,
                <div class="sign-space"></div>
                ( ____________________ )
            </td>
            <td>
                Diterima oleh,
                <div class="sign-space"></div>
                ( ____________________ )
            </td>
        </tr>
    </table>
</body>

</html>

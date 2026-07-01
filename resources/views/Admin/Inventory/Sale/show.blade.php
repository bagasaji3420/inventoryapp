@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Detail Penjualan</h4>
    <p class="mb-6 text-muted">{{ $sale->no_faktur }}</p>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">No. Faktur</small>
                    <strong>{{ $sale->no_faktur }}</strong>
                </div>
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Tanggal</small>
                    <strong>{{ $sale->tanggal->format('Y-m-d') }}</strong>
                </div>
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Pelanggan</small>
                    <strong>{{ $sale->customer->nama_pelanggan }}</strong>
                </div>
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Status Pembayaran</small>
                    @php
                        $badge = match ($sale->status_pembayaran) {
                            'lunas' => 'success',
                            'sebagian' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $sale->status_pembayaran)) }}</span>
                </div>
            </div>

            @if ($sale->stockOut?->deliveryNote)
                <div class="row mt-2">
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">No. Surat Jalan</small>
                        <strong>{{ $sale->stockOut->deliveryNote->no_surat }}</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">Status Pengiriman</small>
                        <strong>{{ ucfirst($sale->stockOut->deliveryNote->status) }}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-4">DETAIL BARANG</h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->items as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if ($row->item->foto)
                                        <img src="{{ asset('storage/' . $row->item->foto) }}"
                                            alt="{{ $row->item->nama_barang }}" width="40" height="40"
                                            style="object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:40px;height:40px;background:#eef1f8;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bx bx-package" style="color:#a0b0d0;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $row->item->nama_barang }}</td>
                                <td>{{ $row->qty }}</td>
                                <td>Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th>Rp {{ number_format($sale->total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-4">RIWAYAT PEMBAYARAN</h6>

            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sale->payments as $i => $payment)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $payment->tanggal->format('Y-m-d') }}</td>
                                <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                <td>{{ $payment->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @php
                $totalDibayar = $sale->payments->sum('jumlah');
                $sisa = max($sale->total - $totalDibayar, 0);
            @endphp

            <p class="mb-3">
                Sudah dibayar: <strong>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</strong> &mdash;
                Sisa: <strong>Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
            </p>

            @if ($sale->status_pembayaran !== 'lunas')
                <form action="{{ route('sales.payments.store', $sale->id) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-4">
                        <input type="number" step="0.01" min="0.01" max="{{ $sisa }}" name="jumlah"
                            class="form-control" placeholder="Jumlah bayar" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Tambah Pembayaran</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back"></i> Kembali
    </a>
@endsection

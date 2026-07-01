@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Kartu Stok - {{ $item->nama_barang }}</h4>
    <p class="mb-6 text-muted">{{ $item->kode_barang }}</p>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Stok Saat Ini</small>
                    <h3 class="mb-0">{{ $item->stok }} <small class="text-muted">{{ $item->satuan->nama_satuan ?? '' }}</small></h3>
                </div>
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Stok Minimum</small>
                    <strong>{{ $item->stok_minimum }}</strong>
                </div>
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Jenis</small>
                    <strong>{{ $item->jenis->nama_jenis ?? '-' }}</strong>
                </div>
                <div class="col-md-3 mb-2">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge bg-{{ $item->status === 'aktif' ? 'success' : 'secondary' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h6 class="fw-bold mb-0">RIWAYAT PERGERAKAN STOK</h6>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#filterStockCardModal">
                        <i class="bx bx-filter-alt"></i> Filter Tanggal
                    </button>
                    <a href="{{ route('stock-cards.item-export-excel', ['item' => $item->id, 'tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                        class="btn btn-outline-success btn-sm" title="Download Excel">
                        <i class="bx bxs-file-export"></i>
                    </a>
                    <a href="{{ route('stock-cards.item-report', ['item' => $item->id, 'tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                        target="_blank" class="btn btn-outline-danger btn-sm" title="Print PDF">
                        <i class="bx bxs-file-pdf"></i>
                    </a>
                </div>
            </div>

            @if ($tanggalAwal || $tanggalAkhir)
                <div class="alert alert-info d-flex justify-content-between align-items-center py-2">
                    <span>
                        Filter: {{ $tanggalAwal ?? '...' }} s/d {{ $tanggalAkhir ?? '...' }}
                    </span>
                    <a href="{{ route('stock-cards.show', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i> Reset
                    </a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th>Referensi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockCards as $i => $stockCard)
                            <tr>
                                <td>{{ $stockCards->firstItem() + $i }}</td>
                                <td>{{ $stockCard->tanggal->format('Y-m-d H:i') }}</td>
                                <td>
                                    @php
                                        $badge = match ($stockCard->jenis_transaksi) {
                                            'masuk' => 'success',
                                            'keluar' => 'warning',
                                            default => 'info',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($stockCard->jenis_transaksi) }}
                                    </span>
                                </td>
                                <td>{{ $stockCard->referensi }}</td>
                                <td class="text-success">{{ $stockCard->masuk ? '+' . $stockCard->masuk : '-' }}</td>
                                <td class="text-danger">{{ $stockCard->keluar ? '-' . $stockCard->keluar : '-' }}</td>
                                <td><strong>{{ $stockCard->saldo }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada riwayat pergerakan stok</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $stockCards->links() }}

            <a href="{{ route('stock-cards.index') }}" class="btn btn-secondary mt-3">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterStockCardModal">
        <div class="modal-dialog">
            <form method="GET" action="{{ route('stock-cards.show', $item->id) }}" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Tanggal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Filter Cepat</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-quick-filter="week">
                                Minggu Ini
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-quick-filter="month">
                                Bulan Ini
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggalAwal }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('stock-cards.show', $item->id) }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/custom-js/inventory/date-range-filter.js') }}"></script>
@endsection

@extends('Admin.Layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1">Kartu Stok</h4>
            <p class="mb-0 text-muted">Pilih barang untuk lihat riwayat pergerakan stoknya</p>
        </div>
        <a href="{{ route('stock-cards.report') }}" target="_blank" class="btn btn-outline-danger">
            <i class="bx bxs-file-pdf"></i> Laporan Sisa Stok
        </a>
    </div>

    <div class="row g-4">
        @forelse ($items as $item)
            <div class="col-6 col-lg-3">
                <a href="{{ route('stock-cards.show', $item->id) }}" class="text-decoration-none text-body">
                    @php
                        $totalMasuk  = $item->stockCards->sum('masuk');
                        $totalKeluar = $item->stockCards->sum('keluar');
                    @endphp
                    <div class="card h-100 position-relative">
                        @if ($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}"
                                class="card-img-top"
                                style="height:120px;object-fit:cover;">
                        @else
                            <div style="height:80px;background:linear-gradient(135deg,#e8eef8,#d0daf5);display:flex;align-items:center;justify-content:center;">
                                <i class="bx bx-package" style="font-size:36px;color:#a0b0d0;"></i>
                            </div>
                        @endif
                        <div class="card-body text-center">
                            <h6 class="mb-1 text-truncate" title="{{ $item->nama_barang }}">
                                {{ $item->nama_barang }}
                            </h6>
                            <small class="text-muted d-block mb-2">{{ $item->kode_barang }}</small>

                            {{-- Indikator masuk / keluar bulan ini --}}
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge rounded-pill"
                                    style="background:#d4edda;color:#155724;font-size:10px;font-weight:600;">
                                    <i class="bx bx-up-arrow-alt"></i> +{{ number_format($totalMasuk, 0) }}
                                </span>
                                <span class="badge rounded-pill"
                                    style="background:#f8d7da;color:#721c24;font-size:10px;font-weight:600;">
                                    <i class="bx bx-down-arrow-alt"></i> -{{ number_format($totalKeluar, 0) }}
                                </span>
                            </div>

                            <h3 class="mb-0">{{ $item->stok }}</h3>
                            <small class="text-muted">{{ $item->satuan->nama_satuan ?? '' }}</small>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted">
                        Belum ada data barang
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection

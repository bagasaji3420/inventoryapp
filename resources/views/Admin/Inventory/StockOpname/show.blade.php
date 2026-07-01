@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Detail Stok Opname</h4>
    <p class="mb-6 text-muted">{{ $stockOpname->no_opname }}</p>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <small class="text-muted d-block">No. Opname</small>
                    <strong>{{ $stockOpname->no_opname }}</strong>
                </div>
                <div class="col-md-4 mb-2">
                    <small class="text-muted d-block">Tanggal Opname</small>
                    <strong>{{ $stockOpname->tanggal_opname->format('Y-m-d') }}</strong>
                </div>
                <div class="col-md-4 mb-2">
                    <small class="text-muted d-block">Petugas</small>
                    <strong>{{ $stockOpname->petugas->first_name ?? '-' }} {{ $stockOpname->petugas->last_name ?? '' }}</strong>
                </div>
                <div class="col-md-4 mb-2">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge bg-{{ $stockOpname->status === 'selesai' ? 'success' : 'secondary' }}">
                        {{ ucfirst($stockOpname->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h6 class="fw-bold mb-4">DETAIL BARANG</h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Barang</th>
                            <th>Stok Sistem</th>
                            <th>Stok Fisik</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockOpname->items as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->item->nama_barang }}</td>
                                <td>{{ $row->stok_sistem }}</td>
                                <td>{{ $row->stok_fisik }}</td>
                                <td class="{{ $row->selisih > 0 ? 'text-success' : ($row->selisih < 0 ? 'text-danger' : '') }}">
                                    {{ $row->selisih > 0 ? '+' : '' }}{{ $row->selisih }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>
@endsection

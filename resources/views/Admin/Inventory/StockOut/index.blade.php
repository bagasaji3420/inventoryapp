@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Barang Keluar</h4>
    <p class="mb-6 text-muted">Data Barang Keluar</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockOutModal"
                @cannot('stock-outs.create') disabled @endcannot>
                Tambah Data
            </button>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                    data-bs-target="#filterStockOutModal">
                    <i class="bx bx-filter-alt"></i> Filter Tanggal
                </button>
                <a href="{{ route('stock-outs.export-excel', request()->only(['tanggal_awal', 'tanggal_akhir'])) }}"
                    class="btn btn-outline-success">
                    <i class="bx bxs-file-export"></i> Excel
                </a>
                <a href="{{ route('stock-outs.export-pdf', request()->only(['tanggal_awal', 'tanggal_akhir'])) }}"
                    target="_blank" class="btn btn-outline-danger">
                    <i class="bx bxs-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA BARANG KELUAR</h6>

            @if ($tanggalAwal || $tanggalAkhir)
                <div class="alert alert-info d-flex justify-content-between align-items-center py-2">
                    <span>
                        Filter: {{ $tanggalAwal ?? '...' }} s/d {{ $tanggalAkhir ?? '...' }}
                    </span>
                    <a href="{{ route('stock-outs.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i> Reset
                    </a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jumlah Keluar</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($stockOuts as $stockOut)
                            @foreach ($stockOut->items as $row)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $stockOut->no_transaksi }}</td>
                                    <td>{{ $stockOut->tanggal->format('Y-m-d') }}</td>
                                    <td>{{ $stockOut->customer->nama_pelanggan }}</td>
                                    <td>{{ $row->item->nama_barang }}</td>
                                    <td>{{ $row->unit->nama_satuan }}</td>
                                    <td>{{ $row->qty_input }}</td>
                                    <td>{{ $stockOut->keterangan }}</td>
                                    @if ($loop->first)
                                        <td rowspan="{{ $stockOut->items->count() }}">
                                            @if (!$stockOut->sale_id)
                                                <form action="{{ route('stock-outs.destroy', $stockOut->id) }}"
                                                    method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-icon btn-danger btn-sm shadow-none"
                                                        @cannot('stock-outs.delete') disabled @endcannot>
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-info">Dari Penjualan</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $stockOuts->links() }}
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterStockOutModal">
        <div class="modal-dialog">
            <form method="GET" action="{{ route('stock-outs.index') }}" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Tanggal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Filter Cepat</label>
                        <div class="d-flex flex-wrap gap-2">
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
                    <a href="{{ route('stock-outs.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addStockOutModal">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('stock-outs.store') }}" class="modal-content" id="stockOutForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pelanggan</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required
                                value="{{ now()->toDateString() }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>

                    <label class="form-label">Barang</label>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="stockOutItemsBody"></tbody>
                    </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addStockOutRow">
                        <i class="bx bx-plus"></i> Tambah Barang
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/custom-js/inventory/confirm-delete.js') }}"></script>
    <script src="{{ asset('assets/custom-js/inventory/date-range-filter.js') }}"></script>
    <script>
        window.inventoryItems = @json($items->map(fn($item) => ['id' => $item->id, 'nama_barang' => $item->nama_barang, 'satuan_id' => $item->satuan_id]));
        window.inventoryUnits = @json($units->map(fn($unit) => ['id' => $unit->id, 'nama_satuan' => $unit->nama_satuan]));
    </script>
    <script src="{{ asset('assets/custom-js/inventory/stock-out.js') }}"></script>
@endsection

@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Stok Opname</h4>
    <p class="mb-6 text-muted">Data Stok Opname</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockOpnameModal"
                @cannot('stock-opnames.create') disabled @endcannot>
                Tambah Data
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                data-bs-target="#filterStockOpnameModal">
                <i class="bx bx-filter-alt"></i> Filter Tanggal
            </button>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA STOK OPNAME</h6>

            @if ($tanggalAwal || $tanggalAkhir)
                <div class="alert alert-info d-flex justify-content-between align-items-center py-2">
                    <span>
                        Filter: {{ $tanggalAwal ?? '...' }} s/d {{ $tanggalAkhir ?? '...' }}
                    </span>
                    <a href="{{ route('stock-opnames.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i> Reset
                    </a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Opname</th>
                            <th>Tanggal Opname</th>
                            <th>Petugas</th>
                            <th>Total Item</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockOpnames as $i => $stockOpname)
                            <tr>
                                <td>{{ $stockOpnames->firstItem() + $i }}</td>
                                <td>{{ $stockOpname->no_opname }}</td>
                                <td>{{ $stockOpname->tanggal_opname->format('Y-m-d') }}</td>
                                <td>{{ $stockOpname->petugas->first_name ?? '-' }}</td>
                                <td>{{ $stockOpname->items->count() }}</td>
                                <td>
                                    <span class="badge bg-{{ $stockOpname->status === 'selesai' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($stockOpname->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('stock-opnames.show', $stockOpname->id) }}"
                                        class="btn btn-icon btn-primary btn-sm shadow-none">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $stockOpnames->links() }}
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterStockOpnameModal">
        <div class="modal-dialog">
            <form method="GET" action="{{ route('stock-opnames.index') }}" class="modal-content">
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
                    <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addStockOpnameModal">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('stock-opnames.store') }}" class="modal-content" id="stockOpnameForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stok Opname</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Opname</label>
                        <input type="date" name="tanggal_opname" class="form-control" required
                            value="{{ now()->toDateString() }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Scan Barcode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-barcode-reader fs-5"></i></span>
                            <input type="text" id="opnameBarcodeInput" class="form-control"
                                placeholder="Scan atau ketik barcode lalu tekan Enter..."
                                autocomplete="off" spellcheck="false">
                            <button type="button" class="btn btn-outline-secondary" id="opnameCameraBtn"
                                title="Scan via kamera">
                                <i class="bx bx-camera fs-5"></i>
                            </button>
                            <span class="input-group-text text-muted d-none" id="opnameBarcodeStatus"
                                style="min-width:130px;font-size:12px;"></span>
                        </div>
                        <div id="opnameCameraContainer" class="d-none mt-2 border rounded p-2" style="background:#000;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-white">Arahkan kamera ke barcode...</small>
                                <button type="button" id="opnameStopCameraBtn" class="btn btn-sm btn-danger">
                                    <i class="bx bx-x"></i> Tutup
                                </button>
                            </div>
                            <div id="opnameCameraReader" style="width:100%;max-height:260px;overflow:hidden;"></div>
                        </div>
                    </div>

                    <label class="form-label">Barang</label>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Stok Sistem</th>
                                <th>Stok Fisik</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="stockOpnameItemsBody"></tbody>
                    </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addStockOpnameRow">
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

    <script src="{{ asset('assets/custom-js/inventory/date-range-filter.js') }}"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    @php
        $itemsJson = $items->map(function ($item) {
            return ['id' => $item->id, 'nama_barang' => $item->nama_barang, 'stok' => $item->stok, 'barcode' => $item->barcode];
        });
    @endphp
    <script>
        window.inventoryItems = @json($itemsJson);
    </script>
    <script src="{{ asset('assets/custom-js/inventory/stock-opname.js') }}"></script>
@endsection

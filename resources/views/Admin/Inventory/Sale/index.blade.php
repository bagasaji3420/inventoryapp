@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Kasir Penjualan</h4>
    <p class="mb-6 text-muted">Data Penjualan</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSaleModal"
                @cannot('sales.create') disabled @endcannot>
                Tambah Penjualan
            </button>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                    data-bs-target="#filterSaleModal">
                    <i class="bx bx-filter-alt"></i> Filter Tanggal
                </button>
                <a href="{{ route('sales.export-excel', request()->only(['tanggal_awal', 'tanggal_akhir'])) }}"
                    class="btn btn-outline-success">
                    <i class="bx bxs-file-export"></i> Excel
                </a>
                <a href="{{ route('sales.export-pdf', request()->only(['tanggal_awal', 'tanggal_akhir'])) }}"
                    target="_blank" class="btn btn-outline-danger">
                    <i class="bx bxs-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA PENJUALAN</h6>

            @if ($tanggalAwal || $tanggalAkhir)
                <div class="alert alert-info d-flex justify-content-between align-items-center py-2">
                    <span>
                        Filter: {{ $tanggalAwal ?? '...' }} s/d {{ $tanggalAkhir ?? '...' }}
                    </span>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-x"></i> Reset
                    </a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Faktur</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $i => $sale)
                            <tr>
                                <td>{{ $sales->firstItem() + $i }}</td>
                                <td>{{ $sale->no_faktur }}</td>
                                <td>{{ $sale->tanggal->format('Y-m-d') }}</td>
                                <td>{{ $sale->customer->nama_pelanggan }}</td>
                                <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badge = match ($sale->status_pembayaran) {
                                            'lunas' => 'success',
                                            'sebagian' => 'warning',
                                            default => 'danger',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $sale->status_pembayaran)) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('sales.show', $sale->id) }}"
                                        class="btn btn-icon btn-primary btn-sm shadow-none">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $sales->links() }}
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterSaleModal">
        <div class="modal-dialog">
            <form method="GET" action="{{ route('sales.index') }}" class="modal-content">
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
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kasir -->
    <div class="modal fade" id="addSaleModal">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('sales.store') }}" class="modal-content" id="saleForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">No. Faktur</label>
                            <input type="text" class="form-control" value="Otomatis saat disimpan" disabled>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required
                                value="{{ now()->toDateString() }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pelanggan</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Scan Barcode --}}
                    <div class="mb-3">
                        <label class="form-label">Scan Barcode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-barcode-reader fs-5"></i></span>
                            <input type="text" id="saleBarcodeInput" class="form-control"
                                placeholder="Scan atau ketik barcode lalu tekan Enter..."
                                autocomplete="off" spellcheck="false">
                            <button type="button" class="btn btn-outline-secondary" id="cameraScanBtn"
                                title="Scan via kamera">
                                <i class="bx bx-camera fs-5"></i>
                            </button>
                            <span class="input-group-text text-muted d-none" id="barcodeStatus"
                                style="min-width:130px;font-size:12px;"></span>
                        </div>

                        {{-- Camera preview (hidden by default) --}}
                        <div id="cameraScanContainer" class="d-none mt-2 border rounded p-2"
                            style="background:#000;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-white">Arahkan kamera ke barcode...</small>
                                <button type="button" id="stopCameraBtn"
                                    class="btn btn-sm btn-danger">
                                    <i class="bx bx-x"></i> Tutup
                                </button>
                            </div>
                            <div id="cameraReader" style="width:100%;max-height:260px;overflow:hidden;"></div>
                        </div>
                    </div>

                    <label class="form-label">Barang</label>
                    <div class="mb-2">
                        <select id="saleItemSelect" class="form-select">
                            <option value="">Pilih Barang</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}"
                                    data-harga="{{ $item->harga }}"
                                    data-nama="{{ $item->nama_barang }}"
                                    data-barcode="{{ $item->barcode }}"
                                    data-stok="{{ $item->stok }}"
                                    data-satuan="{{ $item->satuan->nama_satuan }}">
                                    {{ $item->nama_barang }} - Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <small id="saleItemStokInfo" class="text-muted d-none mt-1 d-block"></small>
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <input type="number" id="saleItemQty" class="form-control" placeholder="Jumlah" min="1"
                            value="1" style="max-width:120px;">
                        <button type="button" id="addSaleCartRow" class="btn btn-success flex-grow-1">Tambah</button>
                    </div>

                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="saleCartBody"></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th id="saleTotalDisplay">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    </table>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bayar</label>
                            <input type="number" step="0.01" min="0" name="bayar" id="saleBayar" class="form-control"
                                required value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kembalian</label>
                            <input type="text" id="saleKembalian" class="form-control" disabled value="Rp 0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/custom-js/inventory/date-range-filter.js') }}"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="{{ asset('assets/custom-js/inventory/sale.js') }}"></script>
@endsection

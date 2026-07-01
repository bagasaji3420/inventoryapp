@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Data Barang</h4>
    <p class="mb-6 text-muted">Data Barang</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal"
                @cannot('items.create') disabled @endcannot>
                Tambah Data
            </button>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA BARANG</h6>

            <div class="table-responsive">
                <table id="itemTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Kd Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th>Stok Minimum</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}"
                                            class="item-thumb" style="object-fit:cover;border-radius:8px;">
                                    @else
                                        <div class="item-thumb d-flex align-items-center justify-content-center"
                                            style="background:#eef1f8;border-radius:8px;">
                                            <i class="bx bx-package" style="color:#a0b0d0;font-size:22px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->satuan->nama_satuan }}</td>
                                <td>{{ $item->jenis->nama_jenis }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->stok_minimum }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>
                                    <form action="{{ route('items.toggle-status', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" role="switch"
                                                onchange="this.form.submit()"
                                                {{ $item->status === 'aktif' ? 'checked' : '' }}
                                                @cannot('items.update') disabled @endcannot>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <button type="button" class="btn btn-icon btn-warning btn-sm shadow-none" data-bs-toggle="modal"
                                            data-bs-target="#editItemModal" @cannot('items.update') disabled @endcannot
                                            onclick='setEdit(
                                                {{ $item->id }},
                                                @json($item->kode_barang),
                                                @json($item->barcode),
                                                @json($item->nama_barang),
                                                {{ $item->satuan_id }},
                                                {{ $item->jenis_id }},
                                                {{ $item->harga }},
                                                {{ $item->stok_minimum }},
                                                @json($item->foto ? asset("storage/{$item->foto}") : null)
                                            )'>
                                            <i class="bx bx-edit"></i>
                                        </button>

                                        <a href="{{ route('items.barcode', $item->id) }}" target="_blank"
                                            class="btn btn-icon btn-secondary btn-sm shadow-none" title="Cetak Barcode">
                                            <i class="bx bx-barcode"></i>
                                        </a>

                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-danger btn-sm shadow-none"
                                                @cannot('items.delete') disabled @endcannot>
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addItemModal">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('items.store') }}" class="modal-content" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Barang</label>
                            <input type="text" name="kode_barang" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan</label>
                            <select name="satuan_id" class="form-select" required>
                                <option value="">Pilih Satuan</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis_id" class="form-select" required>
                                <option value="">Pilih Jenis</option>
                                @foreach ($itemTypes as $itemType)
                                    <option value="{{ $itemType->id }}">{{ $itemType->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" step="0.01" min="0" name="harga" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" step="0.01" min="0" name="stok_minimum" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" id="addFoto" class="form-control" accept="image/*">
                        <img id="addFotoPreview" src="" alt="Preview" class="img-thumbnail mt-2 d-none"
                            style="max-width: 150px; max-height: 150px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editItemModal">
        <div class="modal-dialog modal-md">
            <form method="POST" id="editItemForm" class="modal-content" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Barang</label>
                            <input type="text" name="kode_barang" id="editKodeBarang" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" id="editBarcode" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" id="editNamaBarang" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan</label>
                            <select name="satuan_id" id="editSatuanId" class="form-select" required>
                                <option value="">Pilih Satuan</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis_id" id="editJenisId" class="form-select" required>
                                <option value="">Pilih Jenis</option>
                                @foreach ($itemTypes as $itemType)
                                    <option value="{{ $itemType->id }}">{{ $itemType->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" step="0.01" min="0" name="harga" id="editHarga" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" step="0.01" min="0" name="stok_minimum" id="editStokMinimum"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto (kosongkan jika tidak ganti)</label>
                        <input type="file" name="foto" id="editFoto" class="form-control" accept="image/*">
                        <img id="editFotoPreview" src="" alt="Preview" class="img-thumbnail mt-2 d-none"
                            style="max-width: 150px; max-height: 150px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    @include('Admin.Layouts.Parts.datatables')
    <script src="{{ asset('assets/custom-js/inventory/confirm-delete.js') }}"></script>
    <script src="{{ asset('assets/custom-js/inventory/item.js') }}"></script>
    <script src="{{ asset('assets/custom-js/inventory/photo-lightbox.js') }}"></script>
@endsection

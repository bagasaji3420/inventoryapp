@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Jenis Barang</h4>
    <p class="mb-6 text-muted">Data Jenis Barang</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemTypeModal"
                @cannot('item-types.create') disabled @endcannot>
                Tambah Data
            </button>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA JENIS BARANG</h6>

            <table id="itemTypeTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Jenis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemTypes as $i => $itemType)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $itemType->nama_jenis }}</td>
                            <td>
                                <form action="{{ route('item-types.toggle-status', $itemType->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch"
                                            onchange="this.form.submit()"
                                            {{ $itemType->status === 'aktif' ? 'checked' : '' }}
                                            @cannot('item-types.update') disabled @endcannot>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    <button type="button" class="btn btn-icon btn-warning btn-sm shadow-none" data-bs-toggle="modal"
                                        data-bs-target="#editItemTypeModal" @cannot('item-types.update') disabled @endcannot
                                        onclick='setEdit({{ $itemType->id }}, @json($itemType->nama_jenis))'>
                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <form action="{{ route('item-types.destroy', $itemType->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger btn-sm shadow-none"
                                            @cannot('item-types.delete') disabled @endcannot>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="addItemTypeModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('item-types.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis</label>
                        <input type="text" name="nama_jenis" class="form-control" required>
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
    <div class="modal fade" id="editItemTypeModal">
        <div class="modal-dialog">
            <form method="POST" id="editItemTypeForm" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jenis Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis</label>
                        <input type="text" name="nama_jenis" id="editNamaJenis" class="form-control" required>
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
    <script src="{{ asset('assets/custom-js/inventory/item-type.js') }}"></script>
@endsection

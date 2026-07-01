@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Satuan Barang</h4>
    <p class="mb-6 text-muted">Data Satuan Barang</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal"
                @cannot('units.create') disabled @endcannot>
                Tambah Data
            </button>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA SATUAN BARANG</h6>

            <table id="unitTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Satuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $i => $unit)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $unit->nama_satuan }}</td>
                            <td>
                                <form action="{{ route('units.toggle-status', $unit->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch"
                                            onchange="this.form.submit()"
                                            {{ $unit->status === 'aktif' ? 'checked' : '' }}
                                            @cannot('units.update') disabled @endcannot>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    <button type="button" class="btn btn-icon btn-warning btn-sm shadow-none" data-bs-toggle="modal"
                                        data-bs-target="#editUnitModal" @cannot('units.update') disabled @endcannot
                                        onclick='setEdit({{ $unit->id }}, @json($unit->nama_satuan))'>
                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <form action="{{ route('units.destroy', $unit->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger btn-sm shadow-none"
                                            @cannot('units.delete') disabled @endcannot>
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
    <div class="modal fade" id="addUnitModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('units.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Satuan</label>
                        <input type="text" name="nama_satuan" class="form-control" required>
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
    <div class="modal fade" id="editUnitModal">
        <div class="modal-dialog">
            <form method="POST" id="editUnitForm" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Satuan</label>
                        <input type="text" name="nama_satuan" id="editNamaSatuan" class="form-control" required>
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
    <script src="{{ asset('assets/custom-js/inventory/unit.js') }}"></script>
@endsection
